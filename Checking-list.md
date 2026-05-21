1. Login:
    - login using email, password
    - remember me functionality 
    - forgot password functionality 
    - show proper errors 
    - throttling 

    - done

2. Dashboard: 

    - sales analytics reports 
    - purchase analytics reports 
    - brand wise orders 
    - dispatch status 
    - delivery confirmation 
    - grn status 
    - payment status 
    - warehouse inventory 

    - date filter 

3. Profile page: 
    - display staff/admin details
    - edit data option
    - change password option
    - logout option

    - done

4. Logout button: 
    - logout functionality 

    - done

5. permissions: 

    - list permissions 
    - create permission 
    - update permission 
    - delete permission 

6. roles: 

    - list roles 
    - create role 
    - update role 
    - delete role 

    - proper pagination 
    - sorting properly 

    - done

7. warehouses: 

    - list warehouses 
    - create warehouse 
    - update warehouse 
    - delete warehouse 
    - view warehouse
        - products list                  - pending
        - export products functionality  - pending

    - proper pagination
    - sorting properly 
    - tabs switching 
    - multi delete functionality
    - multi toggle status functionality

    - done

8. staff: 

    - list staff 
    - create staff 
    - update staff 
    - delete staff 
    - view staff
        - staff details all     
        - edit button for redirect to edit page
        - permissions list  

    - proper pagination
    - sorting properly 
    - tabs switching 
    - multi delete functionality
    - multi toggle status functionality

    - done

9. customer groups: 
    - list customer groups  - done  
    - create customer groups  - done
    - update customer groups  - done
    - delete customer groups  - done
    - view customer groups
        - list customers in that group  - done
        - add customer (single)         - commented 
        - add customer (bulk)           - done
        - export customers              - done
        - update via excel              - done
        - update via form               - done
        - delete customer               - done
        - multi delete functionality    - done
        - toggle status functionality   - done
        - multi toggle status functionality - done

        - view customer: 
            - display customer information  - done
            - invoices list 
            - sales orders list 
            - payments list 
            - returns list 

        - tabs switching            - done
        - proper pagination         - done
        - sorting properly          - done          

    - proper pagination             - done
    - sorting properly              - done
    - tabs switching                - done
    - delete customer group         - done
    - multi delete functionality    - done
    - toggle status functionality   - done
    - multi toggle status functionality   - done


10. customers (optional): 

11. vendors: 

    - list vendors   - done
    - create vendors - done
    - update vendors - done
    - delete vendors - done
    - view vendors
        - personal details  - done
        - address details   - done
        - orders list:
            - list orders 
            - export button
    
    - proper pagination - done
    - sorting properly - done
    - tabs switching   - done
    - multi delete functionality  - done
    - toggle status functionality   - done
    - multi toggle status functionality   - done


12. products: 
    - list products  - done
    - add products in bulk  - done
    - export products     - done
    - update products     - done
    - update products in bulk   - done
    - delete products  - done
    - multi delete functionality  - done
    
    - proper pagination  - done
    - sorting properly   - done

    Logics: 

    - check vendor is present or not when adding product - done

13. sku mapping:
    - list sku mapping           - done
    - add sku mapping products in bulk   - done
    - export sku mapping products        - done
    - update sku mapping products        - done
    - update sku mapping products in bulk - done
    - delete sku mapping products        - done
    - multi delete functionality         - not available

    - proper pagination      - done 
    - sorting properly       - done 

================================================================================

common layout: Check following functionalities for selected routes (Master Section Start to Master Section End) and correct the same layout for all these view files. 

list 
tab switching
pagination 
toggle status 
multi toggle status 
delete 
multi delete 


create functionality (if available) 
update functionality (if available)
view functionality (if available)

export file functionality (if available)
update via excel functionality (if available)




================================================================================

14. check availability: 
    - fill form and upload excel then submit 
    - display availibility table and export button
    - export file

    Logics:
    - display customer groups and warehouse list (both required)
    - upload excel (proper format) (required)
    - validate all required columns in file (check all column names present and all values filled)
    - 

15. create sales order (block order):
    - fill block quantity column in above exported file
    - create sales order by uploading updated file

    Logics:

16. Auto generate purchase order:
    - auto generate purchase order for sales order 
    - export purchase order file 

    Logics:


17. Custom purchase order:
    - create purchase order by uploading excel file. 
    - export purchase order file 

    Logics:


18. Add vendor PI:
    - upload vendor PI for the same purchase order by filling pi quantity
    - export vendor PI file 
    - update vendor PI file (before receiving quantity)

    Logics:


19. Received products vendor quantity: 
    - update vendor pi by filling received quantity column 
    - upload the same file and update received quantity 
    - re export and update option before submitting
    - submit for admin approval 
    
    Logics: 

20. Received products admin approval:
    - admin approves received products
    - admin can export received products file 
    - admin can update received quantity and re upload 

    Logics: 

21. Update sales order stock (automatically):
    - if the purchase order auto generated from sales order then auto block that quantity for that products. 
    
    Logics: 

22. Update final order quantity: 
    - export sales order file 
    - update final quantity fulfilled column 
    - upload file and update
    
    Logics:

23. Send to packaging: 
    - send for packaging products:
        - final quantity fulfilled > 0 
        - status pending 
        - pagination problem 

    Logics:

24. Packaging List products:
    - list packaging products
    - export packaging products file
    - update final dispatch quantity in excel
    - upload file and submit for admin approval (send to admin approval - status)

    Logics:

25. Admin approval:
    - export packaging products (send to admin approval - status)
    - admin can update packaging products 
    - admin approves products 
    - after approve automatically products will go to shipped status

    Logics:

26. Ready to ship list:
    - list ready to ship products

27. Generate Invoice:
    - generate invoice for sales order

    Logics:

28. Generate E-Invoice:
    - generate e-invoice using invoice

    Logics:

29. Generate E-Way Bill: 
    - generate e-way bill using e-invoice

    Logics:

30. Product Issues List: 
    - display shortage list from vendor and warehouse. 

    Logics:

31. Vendor Return List: 
    - display exceed list from vendor only. 
    - return / stock options 

    Logics:

32. Customer Return List: 
    - if customer returns any products then upload via excel file 
    - update stock accordingly

    Logics:

33. Track Order:
    - search order by sales order id / purchase order id 










=============================================================================================================

1. Case 1: All available

a. Create sales order (full quantity available in warehouse)                         - done
b. block quantity as seen in available column                                        - done
c. create sales order                                                                - done
d. add final fulfill quantity same as in fulfill quantity                            - done
e. send for packaging                                                                - done
f. add packaged quantity same as final filfill also add box count and weight         - done
g. send for admin approval                                                           - done
i. approve order                                                                     - done
j. change status from ready to ship - shipped                                        - done
k. generate invoice                                                                  - done
l. generate e-invoice 
m. generate e-way bill
n. upload other documents                                                            - done
o. change status to complete sales order.                                            - done


2. Case 2: All Available but blocking less quantity

a. Create sales order (full quantity available in warehouse)
b. block quantity as less than available column                                       - change here
c. create sales order 
d. add final fulfill quantity same as in fulfill quantity 
e. send for packaging 
f. add packaged quantity same as final filfill also add box count and weight 
g. send for admin approval 
i. approve order 
j. change status from ready to ship - shipped 
k. generate invoice 
l. generate e-invoice 
m. generate e-way bill
n. upload other documents 
o. change status to complete sales order. 

- done



3. Case 3: All Available but final fulfill less quantity

a. Create sales order (full quantity available in warehouse)
b. block quantity as seen in available column 
c. create sales order 
d. add final fulfill quantity less than fulfill quantity                                      - change here
e. send for packaging 
f. add packaged quantity same as final filfill also add box count and weight 
g. send for admin approval 
i. approve order 
j. change status from ready to ship - shipped 
k. generate invoice 
l. generate e-invoice 
m. generate e-way bill
n. upload other documents 
o. change status to complete sales order. 

- done

4. Case 4: All Available but packaged quantity less

a. Create sales order (full quantity available in warehouse)
b. block quantity as seen in available column 
c. create sales order 
d. add final fulfill quantity same as seen in fulfill quantity
e. send for packaging 
f. add packaged quantity same as final filfill also add box count and weight                                     - change here
g. send for admin approval 
i. approve order 
j. change status from ready to ship - shipped 
k. generate invoice 
l. generate e-invoice 
m. generate e-way bill
n. upload other documents 
o. change status to complete sales order. 

- done


5. Case 5: All not Available

a. Create sales order (half quantity available in warehouse)
b. block quantity as seen in available column 
c. create sales order 
d. auto generate purchase order
e. add vendor pi for purchase order                                
f. add received quantity for purchase order 
g. send for admin approval
h. approve received products
i. add final fulfill quantity same as in fulfill quantity 
j. send for packaging 
k. add packaged quantity same as final filfill also add box count and weight 
l. send for admin approval 
m. approve order 
n. change status from ready to ship - shipped 
o. generate invoice 
p. generate e-invoice 
q. generate e-way bill
r. upload other documents 
s. change status to complete sales order. 



6. Case 6: All not Available but block less quantity

a. Create sales order (half quantity available in warehouse)
b. block quantity as seen in available column                        - change here
c. create sales order 
d. auto generate purchase order
e. add vendor pi for purchase order                                
f. add received quantity for purchase order 
g. send for admin approval
h. approve received products
i. add final fulfill quantity same as in fulfill quantity 
j. send for packaging 
k. add packaged quantity same as final filfill also add box count and weight 
l. send for admin approval 
m. approve order 
n. change status from ready to ship - shipped 
o. generate invoice 
p. generate e-invoice 
q. generate e-way bill
r. upload other documents 
s. change status to complete sales order. 


7. Case 7: All not Available but less vendor pi quantity

a. Create sales order (half quantity available in warehouse)
b. block quantity as seen in available column
c. create sales order 
d. auto generate purchase order
e. add vendor pi for purchase order                                  - change here                      
f. add received quantity for purchase order 
g. send for admin approval
h. approve received products
i. add final fulfill quantity same as in fulfill quantity 
j. send for packaging 
k. add packaged quantity same as final filfill also add box count and weight 
l. send for admin approval 
m. approve order 
n. change status from ready to ship - shipped 
o. generate invoice 
p. generate e-invoice 
q. generate e-way bill
r. upload other documents 
s. change status to complete sales order. 



8. Case 8: All not Available but less vendor pi quantity

a. Create sales order (half quantity available in warehouse)
b. block quantity as seen in available column
c. create sales order 
d. auto generate purchase order
e. add vendor pi for purchase order
f. add received quantity for purchase order                                   - change here
g. send for admin approval
h. approve received products
i. add final fulfill quantity same as in fulfill quantity 
j. send for packaging 
k. add packaged quantity same as final filfill also add box count and weight 
l. send for admin approval 
m. approve order 
n. change status from ready to ship - shipped 
o. generate invoice 
p. generate e-invoice 
q. generate e-way bill
r. upload other documents 
s. change status to complete sales order. 



9. Case 9: All not Available but less vendor pi quantity

a. Create sales order (half quantity available in warehouse)
b. block quantity as seen in available column
c. create sales order 
d. auto generate purchase order
e. add vendor pi for purchase order
f. add received quantity for purchase order
g. send for admin approval
h. approve received products
i. add final fulfill quantity same as in fulfill quantity                                    - change here
j. send for packaging 
k. add packaged quantity same as final filfill also add box count and weight 
l. send for admin approval 
m. approve order 
n. change status from ready to ship - shipped 
o. generate invoice 
p. generate e-invoice 
q. generate e-way bill
r. upload other documents 
s. change status to complete sales order. 




10. Case 10: All not Available but less vendor pi quantity

a. Create sales order (half quantity available in warehouse)
b. block quantity as seen in available column
c. create sales order 
d. auto generate purchase order
e. add vendor pi for purchase order
f. add received quantity for purchase order
g. send for admin approval
h. approve received products
i. add final fulfill quantity same as in fulfill quantity
j. send for packaging 
k. add packaged quantity same as final filfill also add box count and weight                                     - change here
l. send for admin approval 
m. approve order 
n. change status from ready to ship - shipped 
o. generate invoice 
p. generate e-invoice 
q. generate e-way bill
r. upload other documents 
s. change status to complete sales order. 



11. Case 11: All not Available but block quantity as per requirement

a. Create sales order (half quantity available in warehouse)
b. block quantity as seen in available column                                - change here
c. create sales order 
d. auto generate purchase order
e. add vendor pi for purchase order
f. add received quantity for purchase order
g. send for admin approval
h. approve received products
i. add final fulfill quantity same as in fulfill quantity
j. send for packaging 
k. add packaged quantity same as final filfill also add box count and weight
l. send for admin approval 
m. approve order 
n. change status from ready to ship - shipped 
o. generate invoice 
p. generate e-invoice 
q. generate e-way bill
r. upload other documents 
s. change status to complete sales order. 




12. Case 12: All not Available but block quantity as per requirement

a. Create sales order (half quantity available in warehouse)       
b. block quantity as seen in available column                               
c. create sales order 
d. auto generate purchase order                                     - change here
e. add vendor pi for purchase order
f. add received quantity for purchase order
g. send for admin approval
h. approve received products
i. add final fulfill quantity same as in fulfill quantity
j. send for packaging 
k. add packaged quantity same as final filfill also add box count and weight
l. send for admin approval 
m. approve order 
n. change status from ready to ship - shipped 
o. generate invoice 
p. generate e-invoice 
q. generate e-way bill
r. upload other documents 
s. change status to complete sales order. 





13. Case 13: All not Available but block quantity as per requirement

a. Create sales order (half quantity available in warehouse)       
b. block quantity as seen in available column                               
c. create sales order 
d. auto generate purchase order
e. add vendor pi for purchase order                                          - change here
f. add received quantity for purchase order
g. send for admin approval
h. approve received products
i. add final fulfill quantity same as in fulfill quantity
j. send for packaging 
k. add packaged quantity same as final filfill also add box count and weight
l. send for admin approval 
m. approve order 
n. change status from ready to ship - shipped 
o. generate invoice 
p. generate e-invoice 
q. generate e-way bill
r. upload other documents 
s. change status to complete sales order. 




14. Case 14: All not Available but block quantity as per requirement

a. Create sales order (half quantity available in warehouse)       
b. block quantity as seen in available column                               
c. create sales order 
d. auto generate purchase order
e. add vendor pi for purchase order
f. add received quantity for purchase order                                          - change here
g. send for admin approval
h. approve received products
i. add final fulfill quantity same as in fulfill quantity
j. send for packaging 
k. add packaged quantity same as final filfill also add box count and weight
l. send for admin approval 
m. approve order 
n. change status from ready to ship - shipped 
o. generate invoice 
p. generate e-invoice 
q. generate e-way bill
r. upload other documents 
s. change status to complete sales order. 





15. Case 15: All not Available but block quantity as per requirement

a. Create sales order (half quantity available in warehouse)       
b. block quantity as seen in available column                               
c. create sales order 
d. auto generate purchase order
e. add vendor pi for purchase order
f. add received quantity for purchase order
g. send for admin approval
h. approve received products
i. add final fulfill quantity same as in fulfill quantity                                          - change here
j. send for packaging 
k. add packaged quantity same as final filfill also add box count and weight
l. send for admin approval 
m. approve order 
n. change status from ready to ship - shipped 
o. generate invoice 
p. generate e-invoice 
q. generate e-way bill
r. upload other documents 
s. change status to complete sales order. 






16. Case 16: All not Available but block quantity as per requirement

a. Create sales order (half quantity available in warehouse)       
b. block quantity as seen in available column                               
c. create sales order 
d. auto generate purchase order
e. add vendor pi for purchase order
f. add received quantity for purchase order
g. send for admin approval
h. approve received products
i. add final fulfill quantity same as in fulfill quantity
j. send for packaging 
k. add packaged quantity same as final filfill also add box count and weight                                          - change here
l. send for admin approval 
m. approve order 
n. change status from ready to ship - shipped 
o. generate invoice 
p. generate e-invoice 
q. generate e-way bill
r. upload other documents 
s. change status to complete sales order. 

