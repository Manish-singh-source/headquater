# Access Control

The Access Control module allows administrators to manage **staff members**, define **roles**, and control what actions each user can perform in the system.

This area is divided into two main sections:

- **Staffs**
- **Roles**

---

# Staffs

> **Important:** You must create roles before adding staff members.

Staff management allows you to create users, assign warehouses, and define their permissions through roles.

---

## Staff Listing

The Staff Listing page displays all staff members available in the system.

![Staff List](/docsimages/staffs-list.png)

### Available Actions

#### Toggle Status
You can activate or deactivate a staff member using the status switch.

- **Active** → user can log in  
- **Inactive** → user cannot access the system

![Staff List](/docsimages/active-inactive.png)

---

#### Multi Delete
Select multiple staff members and delete them in one action.

> ⚠️ Use carefully. This action may not be reversible.

![Multi Delete Staff](/docsimages/multi-delete.png)

---

#### Quick View / Edit
Action buttons allow you to:
- View details
- Edit information
- Delete the staff member

![Staff List](/docsimages/view-edit-delete.png)

---

---

## Create New Staff Member

This page is used to register a new staff account.

![Create Staff](/vendor/larecipe/assets/img/create-staff.png)

### Steps

1. Enter staff personal details.
2. Select the **Role**.
3. Select the **Warehouse**.
4. Fill in login or additional information.
5. Click **Submit**.

### What happens after submit?
The system creates the account and applies permissions based on the selected role.

---

---

## View Staff Details

This screen provides full information about the selected staff member.

![View Staff](/vendor/larecipe/assets/img/view-staff.png)

### Information Displayed
- Personal details  
- Contact / account information  
- Assigned role  
- Permissions inherited from the role  

This page is read-only.

---

---

## Edit Staff Details

Allows updating existing staff information.

![Edit Staff](/vendor/larecipe/assets/img/edit-staff.png)

### You can modify
- Personal details  
- Warehouse  
- Role  

> The form is the same as **Create New Staff Member**.

---

---

## Delete Staff Member

Deletes the selected staff member from the system.

The user will immediately lose access.

![Delete Staff](/docsimages/delete.png)

---

---

# Roles

Roles are used to group permissions.  
Instead of assigning permissions one by one to each user, you assign a role.

Example:
- Admin  
- Manager  
- Warehouse Operator  

---

## Role Listing

Displays all created roles.

![Role List](/docsimages/roles-list.png)

From here you can create, edit, or delete roles.

---

---

## Create New Role

Used to define new access levels.

![Create Role](/vendor/larecipe/assets/img/create-role.png)

### Steps

1. Enter **Role Name**.
2. Select permissions.
3. Click **Submit**.

### Result
Any staff member assigned to this role will receive these permissions.

---

---

## Edit Role

Modify an existing role.

![Edit Role](/vendor/larecipe/assets/img/edit-role.png)

### You can change
- Role name  
- Permissions  
- Or both  

Changes will affect all users assigned to this role.

---

---

## Delete Role

Removes the role from the system.

> ⚠️ Make sure no staff members depend on this role before deleting.

---

