<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\Customer;
use App\Models\CustomerGroupMember;
use App\Models\CustomerReturn;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\SalesOrder;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Spatie\SimpleExcel\SimpleExcelReader;

class CustomerController extends Controller
{
    /**
     * Display all customers list
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $status = $request->query('status');

            $customers = Customer::query();

            if (! is_null($status)) {
                $status = (int) $status;
                if ($status === 1) {
                    $customers->active();
                } elseif ($status === 0) {
                    $customers->inactive();
                }
            }

            $customers = $customers->latest()
                ->get();

            $totalCustomersCount = Customer::count();
            $activeCustomersCount = Customer::where('status', 1)->count();
            $inactiveCustomersCount = Customer::where('status', 0)->count();

            return view('customer.index', compact('customers', 'status', 'totalCustomersCount', 'activeCustomersCount', 'inactiveCustomersCount'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error retrieving customers: ' . $e->getMessage());
        }
    }

    /**
     * Show create customer form
     *
     * @param  int  $g_id  - Customer group ID
     * @return \Illuminate\View\View
     */
    public function create($g_id = null)
    {
        $group_id = $g_id;
        $countries = Country::all();
        $states = State::where('country_id', old('shippingCountry'))->get();
        $cities = City::where('state_id', old('shippingState'))->get();

        return view('customer.create', compact(/* other data */'group_id', 'countries', 'states', 'cities'));
    }

    /**
     * Bulk import customers from CSV file
     *
     * @param  int  $g_id  - Customer group ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeBulk(Request $request, $g_id)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:xlsx,xls', // 5MB max
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $file = $request->file('csv_file');
        if (! $file) {
            return redirect()->back()->with('error', 'Please upload a CSV file.');
        }

        DB::beginTransaction();

        try {
            $filePath = $file->getPathname();
            $fileExtension = $file->getClientOriginalExtension();

            $reader = SimpleExcelReader::create($filePath, $fileExtension);

            $insertCount = 0;
            $skipCount = 0;
            $errors = [];
            $rowNumber = 0;
            $totalRows = 0;

            // Check if file has any data
            $allRows = $reader->getRows()->toArray();
            $totalRows = count($allRows);

            if ($totalRows === 0) {
                DB::rollBack();

                return redirect()->back()->with('error', 'The uploaded Excel file is empty. Please add customer data and try again.');
            }

            // Check if first row has the required column
            $firstRow = $allRows[0] ?? [];
            if (! isset($firstRow['Facility Name'])) {
                DB::rollBack();
                $availableColumns = implode(', ', array_keys($firstRow));

                return redirect()->back()->with('error', 'Invalid Excel format. The file must have a "Facility Name" column. Found columns: ' . ($availableColumns ?: 'None'));
            }

            foreach ($allRows as $record) {
                $rowNumber++;

                try {
                    // Validate required field: Facility Name
                    if (! isset($record['Facility Name']) || empty(trim($record['Facility Name']))) {
                        $errors[] = "Row {$rowNumber}: Facility Name is required";
                        $skipCount++;

                        continue;
                    }

                    $facilityName = trim($record['Facility Name']);
                    $email = strtolower(trim($record['Email'] ?? ''));
                    $gstin = strtoupper(trim($record['GSTIN'] ?? ''));
                    $pan = strtoupper(trim($record['PAN'] ?? ''));

                    // Check if customer already exists by facility name
                    $existingCustomer = Customer::where('facility_name', $facilityName)->first();

                    if ($existingCustomer) {
                        // Check if already added to this group
                        $existingMember = CustomerGroupMember::where([
                            'customer_id' => $existingCustomer->id,
                            'customer_group_id' => $g_id,
                        ])->first();

                        if (! $existingMember) {
                            CustomerGroupMember::create([
                                'customer_id' => $existingCustomer->id,
                                'customer_group_id' => $g_id,
                            ]);
                            $insertCount++;
                        } else {
                            $skipCount++;
                        }
                    } else {
                        // Create new customer
                        $customer = Customer::create([
                            'facility_name' => $facilityName,
                            'client_name' => trim($record['Client Name'] ?? ''),
                            'contact_name' => trim($record['Contact Name'] ?? ''),
                            'email' => $email,
                            'contact_no' => trim($record['Contact No'] ?? ''),
                            'gstin' => $gstin,
                            'pan' => $pan,
                            'company_name' => trim($record['Company Name'] ?? ''),
                            'billing_address' => trim($record['Billing Address'] ?? ''),
                            'billing_country' => trim($record['Billing Country'] ?? ''),
                            'billing_state' => trim($record['Billing State'] ?? ''),
                            'billing_city' => trim($record['Billing City'] ?? ''),
                            'billing_zip' => trim($record['Billing Zip'] ?? ''),
                            'shipping_address' => trim($record['Shipping Address'] ?? ''),
                            'shipping_country' => trim($record['Shipping Country'] ?? ''),
                            'shipping_state' => trim($record['Shipping State'] ?? ''),
                            'shipping_city' => trim($record['Shipping City'] ?? ''),
                            'shipping_zip' => trim($record['Shipping Zip'] ?? ''),
                            'status' => '1',
                        ]);

                        // Add customer to group
                        CustomerGroupMember::create([
                            'customer_id' => $customer->id,
                            'customer_group_id' => $g_id,
                        ]);

                        $insertCount++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                    $skipCount++;

                    continue;
                }
            }

            // Check if any records were processed
            if ($insertCount === 0 && $skipCount > 0) {
                DB::rollBack();

                $errorMessage = ! empty($errors)
                    ? 'Failed to import customers. Errors: ' . implode('; ', array_slice($errors, 0, 5))
                    : 'No valid data found in the Excel file. Please ensure the file has customer data with at least a "Facility Name" column filled.';

                if ($skipCount > 5) {
                    $errorMessage .= " (Showing first 5 errors out of {$skipCount} total issues)";
                }

                return redirect()->back()->with('error', $errorMessage);
            }

            if ($insertCount === 0 && $skipCount === 0) {
                DB::rollBack();

                return redirect()->back()->with('error', 'No data rows found in the Excel file. Please check the file format and ensure it contains customer data.');
            }

            DB::commit();

            // Build success message
            $message = "CSV imported successfully! Added {$insertCount} customer(s)";
            if ($skipCount > 0) {
                $message .= ", Skipped {$skipCount} record(s)";
            }
            if (! empty($errors)) {
                $errorSummary = implode('; ', array_slice($errors, 0, 3));
                $message .= ". First errors: {$errorSummary}";
            }

            activity()->log("Customer Group {$g_id} updated with {$insertCount} customers by " . Auth::user()->name);

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('CSV Import Error: ' . $e->getMessage(), [
                'group_id' => $g_id,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'CSV import failed: ' . $e->getMessage());
        }
    }

    /**
     * Store single customer from form
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'facility_name' => 'required|min:3|max:100|unique:customers,facility_name',
            'client_name' => 'required|min:3|max:100',
            'contact_name' => 'required|min:3|max:100',
            'email' => 'required|email|unique:customers,email',
            'contact_no' => 'required|digits:10',
            'gstin' => 'required|min:15',
            'pan' => 'required|min:10',
            'company_name' => 'nullable|min:3|max:100',
            'shipping_address' => 'nullable|min:5|max:255',
            'shipping_country' => 'nullable|min:2|max:100',
            'shipping_state' => 'nullable|min:2|max:100',
            'shipping_city' => 'nullable|min:2|max:100',
            'shipping_zip' => 'nullable|min:4|max:10',
            'billing_address' => 'nullable|min:5|max:255',
            'billing_country' => 'nullable|min:2|max:100',
            'billing_state' => 'nullable|min:2|max:100',
            'billing_city' => 'nullable|min:2|max:100',
            'billing_zip' => 'nullable|min:4|max:10',
            'status' => 'nullable|in:1,0',
        ]);

        if ($validator->fails()) {
            // Check if request expects JSON (AJAX request)
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            // Create customer
            $customer = Customer::create([
                'facility_name' => trim($request->facility_name),
                'client_name' => trim($request->client_name),
                'contact_name' => trim($request->contact_name),
                'email' => strtolower(trim($request->email)),
                'contact_no' => trim($request->contact_no),
                'company_name' => trim($request->company_name ?? ''),
                'gstin' => strtoupper(trim($request->gstin)),
                'pan' => strtoupper(trim($request->pan)),
                'status' => $request->status ?? '1',
                'shipping_address' => trim($request->shipping_address ?? ''),
                'shipping_country' => trim($request->shipping_country ?? ''),
                'shipping_state' => trim($request->shipping_state ?? ''),
                'shipping_city' => trim($request->shipping_city ?? ''),
                'shipping_zip' => trim($request->shipping_zip ?? ''),
                'billing_address' => trim($request->billing_address ?? ''),
                'billing_country' => trim($request->billing_country ?? ''),
                'billing_state' => trim($request->billing_state ?? ''),
                'billing_city' => trim($request->billing_city ?? ''),
                'billing_zip' => trim($request->billing_zip ?? ''),
            ]);

            // Add to customer group
            if ($request->filled('group_id')) {
                CustomerGroupMember::create([
                    'customer_group_id' => $request->group_id,
                    'customer_id' => $customer->id,
                ]);
            }

            DB::commit();

            activity()
                ->performedOn($customer)
                ->causedBy(Auth::user())
                ->log('Customer created');

            // Check if request expects JSON (AJAX request)
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Customer created successfully.',
                    'customer' => $customer,
                ]);
            }

            if (! $request->filled('group_id')) {
                return redirect()->route('customer.index')
                    ->with('success', 'Customer added successfully.');
            }
            return redirect()->route('customer.groups.view', $request->group_id)
                ->with('success', 'Customer added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Customer Creation Error: ' . $e->getMessage());

            // Check if request expects JSON (AJAX request)
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Failed to add customer: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Store individual customer (without group requirement)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeIndividual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'facility_name' => 'required|min:3|max:100|unique:customers,facility_name',
            'client_name' => 'required|min:3|max:100',
            'contact_name' => 'required|min:3|max:100',
            'email' => 'required|email|unique:customers,email',
            'contact_no' => 'required|digits:10',
            'gstin' => 'required|min:15',
            'pan' => 'required|min:10',
            'group_id' => 'nullable|exists:customer_groups,id',
            'company_name' => 'nullable|min:3|max:100',
            'shipping_address' => 'nullable|min:5|max:255',
            'shipping_country' => 'nullable|min:2|max:100',
            'shipping_state' => 'nullable|min:2|max:100',
            'shipping_city' => 'nullable|min:2|max:100',
            'shipping_zip' => 'nullable|min:4|max:10',
            'billing_address' => 'nullable|min:5|max:255',
            'billing_country' => 'nullable|min:2|max:100',
            'billing_state' => 'nullable|min:2|max:100',
            'billing_city' => 'nullable|min:2|max:100',
            'billing_zip' => 'nullable|min:4|max:10',
            'status' => 'nullable|in:1,0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Create customer
            $customer = Customer::create([
                'facility_name' => trim($request->facility_name),
                'client_name' => trim($request->client_name),
                'contact_name' => trim($request->contact_name),
                'email' => strtolower(trim($request->email)),
                'contact_no' => trim($request->contact_no),
                'company_name' => trim($request->company_name ?? ''),
                'gstin' => strtoupper(trim($request->gstin)),
                'pan' => strtoupper(trim($request->pan)),
                'status' => $request->status ?? '1',
                'shipping_address' => trim($request->shipping_address ?? ''),
                'shipping_country' => trim($request->shipping_country ?? ''),
                'shipping_state' => trim($request->shipping_state ?? ''),
                'shipping_city' => trim($request->shipping_city ?? ''),
                'shipping_zip' => trim($request->shipping_zip ?? ''),
                'billing_address' => trim($request->billing_address ?? ''),
                'billing_country' => trim($request->billing_country ?? ''),
                'billing_state' => trim($request->billing_state ?? ''),
                'billing_city' => trim($request->billing_city ?? ''),
                'billing_zip' => trim($request->billing_zip ?? ''),
            ]);

            // Add to customer group if provided
            if ($request->filled('group_id')) {
                CustomerGroupMember::create([
                    'customer_group_id' => $request->group_id,
                    'customer_id' => $customer->id,
                ]);
            }

            DB::commit();

            activity()
                ->performedOn($customer)
                ->causedBy(Auth::user())
                ->log('Individual customer created');

            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully.',
                'customer' => $customer,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Individual Customer Creation Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show edit customer form
     *
     * @param  int  $id  - Customer ID
     * @param  int  $group_id  - Customer group ID
     * @return \Illuminate\View\View
     */
    public function edit($id, $group_id)
    {
        $customer = Customer::findOrFail($id);
        $countries = Country::all();
        $states = State::all();
        $cities = City::all();

        return view('customer.edit', compact('customer', 'group_id', 'countries', 'states', 'cities'));
    }

    /**
     * Update customer details
     *
     * @param  int  $id  - Customer ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'group_id' => 'required|exists:customer_groups,id',
            'facility_name' => 'required|min:3|max:100',
            'client_name' => 'required|min:3|max:100',
            'contact_name' => 'required|min:3|max:100',
            'email' => 'required|email|unique:customers,email,' . $id,
            'contact_no' => 'required|digits:10',
            'gstin' => 'required|min:15',
            'pan' => 'required|min:10',
            'company_name' => 'nullable|min:3|max:100',
            'shipping_address' => 'nullable|min:5|max:255',
            'shipping_country' => 'nullable|min:2|max:100',
            'shipping_state' => 'nullable|min:2|max:100',
            'shipping_city' => 'nullable|min:2|max:100',
            'shipping_zip' => 'nullable|min:4|max:10',
            'billing_address' => 'nullable|min:5|max:255',
            'billing_country' => 'nullable|min:2|max:100',
            'billing_state' => 'nullable|min:2|max:100',
            'billing_city' => 'nullable|min:2|max:100',
            'billing_zip' => 'nullable|min:4|max:10',
            'status' => 'nullable|in:1,0',
        ]);

        if ($validator->fails()) {
            return back()->with('errors', $validator->errors())->withInput();
        }

        try {
            DB::beginTransaction();

            $customer = Customer::where('id', $id)->where('email', strtolower(trim($request->email)))->first();

            $oldData = $customer->toArray();

            // Update customer
            $customer->update([
                'facility_name' => trim($request->facility_name),
                'client_name' => trim($request->client_name),
                'contact_name' => trim($request->contact_name),
                'contact_no' => trim($request->contact_no),
                'company_name' => trim($request->company_name ?? ''),
                'gstin' => strtoupper(trim($request->gstin)),
                'pan' => strtoupper(trim($request->pan)),
                'status' => $request->status ?? 'active',
                'shipping_address' => trim($request->shipping_address ?? ''),
                'shipping_country' => trim($request->shipping_country ?? ''),
                'shipping_state' => trim($request->shipping_state ?? ''),
                'shipping_city' => trim($request->shipping_city ?? ''),
                'shipping_zip' => trim($request->shipping_zip ?? ''),
                'billing_address' => trim($request->billing_address ?? ''),
                'billing_country' => trim($request->billing_country ?? ''),
                'billing_state' => trim($request->billing_state ?? ''),
                'billing_city' => trim($request->billing_city ?? ''),
                'billing_zip' => trim($request->billing_zip ?? ''),
            ]);

            DB::commit();

            activity()->log("Customer {$customer->id} ({$customer->facility_name}) updated by " . Auth::user()->name);

            return redirect()->route('customer.groups.view', $request->group_id)
                ->with('success', 'Customer updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Customer Update Error: ' . $e->getMessage());

            return back()->with('error', 'Failed to update customer: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Delete single customer
     *
     * @param  int  $id  - Customer ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $facilityName = $customer->facility_name;

            DB::beginTransaction();

            // Delete customer group memberships first (foreign key constraint)
            CustomerGroupMember::where('customer_id', $id)->delete();

            // Delete customer
            $customer->delete();

            DB::commit();

            activity()->log("Customer {$id} ({$facilityName}) deleted by " . Auth::user()->name);

            return redirect()->back()->with('success', 'Customer deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Customer Delete Error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Failed to delete customer: ' . $e->getMessage());
        }
    }

    /**
     * Show customer details with comprehensive related data
     *
     * @param  int  $id  - Customer ID
     * @return \Illuminate\View\View
     */
    public function detail($id)
    {
        try {
            // Get customer with basic relationships
            $customerDetails = Customer::with([
                'groupInfo.customerGroup',
            ])->findOrFail($id);

            // Calculate customer metrics
            $customerMetrics = [
                'total_sales' => 0,
                'total_invoices' => 0,
                'pending_payments' => 0,
                'total_products' => 0,
            ];

            // Get customer invoices with relationships
            $customerInvoices = Invoice::with([
                'warehouse',
                'salesOrder',
                'payments',
                'details.product',
            ])
                ->where('customer_id', $id)
                ->orderBy('invoice_date', 'desc')
                ->get();

            // Calculate metrics from invoices
            $customerMetrics['total_invoices'] = $customerInvoices->count();
            $customerMetrics['total_sales'] = $customerInvoices->sum('total_amount');
            $customerMetrics['total_products'] = $customerInvoices->sum(function ($invoice) {
                return $invoice->details->sum('quantity');
            });

            // Calculate pending payments
            $customerMetrics['pending_payments'] = $customerInvoices->sum(function ($invoice) {
                return $invoice->total_amount - $invoice->payments->sum('amount');
            });

            // Get customer sales orders
            $customerOrders = SalesOrder::with([
                'warehouse',
                'customerGroup',
                'orderedProducts.product',
                'orderedProducts.tempOrder',
            ])
                ->whereHas('orderedProducts', function ($query) use ($id) {
                    $query->where('customer_id', $id);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            // Get customer payments
            $customerPayments = Payment::with([
                'invoice.salesOrder',
            ])
                ->whereHas('invoice', function ($query) use ($id) {
                    $query->where('customer_id', $id);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            // Get customer returns
            $customerReturns = CustomerReturn::with([
                'salesOrder',
                'warehouse',
                'product',
            ])
                ->whereHas('salesOrder.orderedProducts', function ($query) use ($id) {
                    $query->where('customer_id', $id);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            return view('customer.detail-view', compact(
                'customerDetails',
                'customerMetrics',
                'customerInvoices',
                'customerOrders',
                'customerPayments',
                'customerReturns'
            ));
        } catch (\Exception $e) {
            Log::error('Customer Detail Error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Customer not found or error loading details.');
        }
    }

    /**
     * Show user profile
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        $user = Auth::user();
        return view('user-profile', compact('user'));
    }

    /**
     * Update user profile information
     *
     * @param  int  $id  - User ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateuser(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'fname' => 'required|min:3|max:50',
            'lname' => 'required|min:3|max:50',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|digits:10',
            'country' => 'nullable|min:2|max:50',
            'state' => 'nullable|min:2|max:50',
            'city' => 'nullable|min:2|max:50',
            'pincode' => 'nullable|digits:6',
            'current_address' => 'nullable|min:5|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            $data = [
                'fname' => trim($request->fname),
                'lname' => trim($request->lname),
                'email' => strtolower(trim($request->email)),
                'phone' => trim($request->phone),
                'country' => trim($request->country ?? ''),
                'state' => trim($request->state ?? ''),
                'city' => trim($request->city ?? ''),
                'pincode' => trim($request->pincode ?? ''),
                'current_address' => trim($request->current_address ?? ''),
            ];

            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');

                // Generate unique filename with timestamp and unique ID
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                // Create directory if it doesn't exist
                $uploadPath = public_path('uploads/images/profile');
                if (! file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                // Delete old image if exists
                if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                    unlink(public_path($user->profile_image));
                }

                // Move uploaded image
                $image->move($uploadPath, $imageName);
                $data['profile_image'] = 'uploads/images/profile/' . $imageName;
            }

            $user->update($data);

            DB::commit();

            activity()->log("User {$user->id} ({$user->email}) profile updated by " . Auth::user()->name);

            return redirect()->route('user-profile')->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Profile Update Error: ' . $e->getMessage());

            return back()->with('error', 'Failed to update profile: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Delete selected customers from a group
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteSelected(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required',
            'groupId' => 'nullable|exists:customer_groups,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Invalid data provided.');
        }

        DB::beginTransaction();

        try {
            // Parse IDs (handle both array and comma-separated string)
            $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

            // Delete selected customer group members
            if ($request->filled('groupId')) {
                $deletedCount = CustomerGroupMember::where('customer_group_id', $request->groupId)
                    ->whereIn('customer_id', $ids)
                    ->delete();
            } else {
                $deletedCount = Customer::destroy($ids);
            }

            DB::commit();

            activity()
                ->causedBy(Auth::user())
                ->log("Deleted {$deletedCount} customer(s) from group {$request->groupId}");

            if ($deletedCount > 0) {
                return redirect()->back()->with('success', "Successfully deleted {$deletedCount} customer(s).");
            }

            return redirect()->back()->with('warning', 'No customers found to delete.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting customers: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Toggle customer status
     */
    public function toggleStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:customers,id',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid data'], 422);
        }

        DB::beginTransaction();

        try {
            $customer = Customer::findOrFail($request->id);
            $customer->status = $request->status;
            $customer->save();

            DB::commit();

            activity()
                ->performedOn($customer)
                ->causedBy(Auth::user())
                ->log('Customer status changed to ' . ($request->status == '1' ? 'Active' : 'Inactive'));

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error toggling customer status: ' . $e->getMessage());

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Change status of multiple customers
     */
    public function bulkStatusChange(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|string',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Invalid data provided.');
        }

        DB::beginTransaction();

        try {
            $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

            if (empty($ids)) {
                return redirect()->back()->with('error', 'No customers selected.');
            }

            $updated = Customer::whereIn('id', $ids)->update(['status' => $request->status]);

            DB::commit();

            activity()
                ->causedBy(Auth::user())
                ->log('Changed status of ' . $updated . ' customers to ' . ($request->status == '1' ? 'Active' : 'Inactive'));

            return redirect()->back()->with('success', "Successfully updated status of {$updated} customer(s).");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error changing customer status: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
