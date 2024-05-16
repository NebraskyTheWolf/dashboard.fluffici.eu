<?php

return [
    // UserListLayout.php
    'screen.user.name' => 'Username',
    'screen.user.email' => 'Email',
    'screen.user.email.placeholder' => 'example@fluffici.eu',
    'screen.user.created' => 'Account Created',
    'screen.user.updated_at' => 'Last Updated',
    'screen.user.actions.title' => 'Actions',
    'screen.user.actions.submenu.edit' => 'Edit',
    'screen.user.actions.submenu.delete' => 'Delete',
    'screen.user.actions.submenu.delete.confirm' => 'Deleting your account will permanently remove all associated data. Please ensure to download any important information before proceeding.',

    // UserPasswordLayout.php
    'screen.user.password.title' => 'Password',
    'screen.user.password.placeholder.one' => 'Leave blank to keep current password',
    'screen.user.password.placeholder.two' => 'Enter new password',

    // UserRoleLayout.php
    'screen.user.roles.title' => 'User Roles',
    'screen.user.roles.help' => 'Assign user to specific roles.',

    // ProfilePasswordLayout.php
    'screen.user.old_password.title' => 'Current Password',
    'screen.user.old_password.help' => 'Enter your current password.',
    'screen.user.old_password.placeholder' => 'Enter current password',

    'screen.user.new_password.title' => 'New Password',
    'screen.user.new_password.placeholder' => 'Enter new password',

    'screen.user.confirm_password.title' => 'Confirm New Password',
    'screen.user.confirm_password.help' => 'A strong password should be at least 15 characters long and include numbers and lowercase letters.',

    'screen.user.common_password.placeholder' => 'Enter current password',

    // UserEditScreen
    'screen.edit.title' => 'Edit User',
    'screen.edit.title.create' => 'Create User',
    'screen.edit.descriptions' => 'Manage user profile and privileges, including associated roles and permissions.',

    'screen.edit.button.remove' => 'Delete',
    'screen.edit.button.save' => 'Save Changes',

    'screen.edit.layout.information.title' => 'Profile Information',
    'screen.edit.layout.information.descriptions' => 'Update user profile information and email address.',

    'screen.edit.layout.password.title' => 'Change Password',
    'screen.edit.layout.password.descriptions' => 'Ensure your account security by setting a strong password.',

    'screen.edit.layout.roles.title' => 'Assign Roles',
    'screen.edit.layout.roles.descriptions' => 'Assign roles to the user for specific access rights.',

    'screen.edit.layout.permissions.title' => 'Set Permissions',
    'screen.edit.layout.permissions.descriptions' => 'Grant additional permissions to the user.',

    'screen.edit.toast.updated' => 'User profile updated.',
    'screen.edit.toast.removed' => 'User account deleted.',

    // UserListScreen
    'screen.list.title' => 'User Management',
    'screen.list.descriptions' => 'View and manage all registered users, including profiles and privileges.',
    'screen.list.button.invite' => 'Invite New User',

    'screen.list.toast.save' => 'User changes saved.',
    'screen.list.toast.remove' => 'User removed.',

    // UserProfileScreen
    'screen.profile.title' => 'My Account',
    'screen.profile.descriptions' => 'Update your account details such as name, email address, and password',

    'screen.profile.button.back' => 'Back to My Account',
    'screen.profile.button.logout' => 'Logout',
    'screen.profile.button.save' => 'Save Changes',

    'screen.profile.layout.information.title' => 'Profile Information',
    'screen.profile.layout.information.descriptions' => 'Update your account\'s profile information and email address.',

    'screen.profile.layout.password.title' => 'Change Password',
    'screen.profile.layout.password.descriptions' => 'Ensure your account security by setting a strong password.',

    'screen.profile.toast.saved' => 'Profile updated successfully.',
    'screen.profile.toast.password_changed' => 'Password changed successfully.',
];
