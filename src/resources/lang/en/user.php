<?php

return [
    // UserListLayout.php
    'screen.user.name' => 'Username',
    'screen.user.email' => 'Email',
    'screen.user.email.placeholder' => 'example@fluffici.eu',
    'screen.user.created' => 'Created at',
    'screen.user.updated_at' => 'Last update',
    'screen.user.actions.title' => 'Actions',
    'screen.user.actions.submenu.edit' => 'Edit',
    'screen.user.actions.submenu.delete' => 'Delete',
    'screen.user.actions.submenu.delete.confirm' => 'Once the account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.',

    // UserPasswordLayout.php
    'screen.user.password.title' => 'Password',
    'screen.user.password.placeholder.one' => 'Leave empty to keep current password',
    'screen.user.password.placeholder.two' => 'Enter the password to be set',

    // UserRoleLayout.php
    'screen.user.roles.title' => 'Roles',
    'screen.user.roles.help' => 'Specify which groups this account should belong to',

    // ProfilePasswordLayout.php
    'screen.user.old_password.title' => 'Current password',
    'screen.user.old_password.help' => 'This is your password set at the moment.',
    'screen.user.old_password.placeholder' => 'Enter the old password',

    'screen.user.new_password.title' => 'New password',
    'screen.user.new_password.placeholder' => 'Enter the current password',

    'screen.user.confirm_password.title' => 'Confirm new password',
    'screen.user.confirm_password.help' => 'A good password is at least 15 characters or at least 8 characters long, including a number and a lowercase letter.',

    'screen.user.common_password.placeholder' => 'Enter the current password',

    // UserEditScreen
    'screen.edit.title' => 'Edit User',
    'screen.edit.title.create' => 'Create User',
    'screen.edit.descriptions' => 'User profile and privileges, including their associated role.',

    'screen.edit.button.remove' => 'Remove',
    'screen.edit.button.save' => 'Save',

    'screen.edit.layout.information.title' => 'Profile Information',
    'screen.edit.layout.information.descriptions' => 'Update your account\'s profile information and email address.',

    'screen.edit.layout.password.title' => 'Password',
    'screen.edit.layout.password.descriptions' => 'Ensure your account is using a long, random password to stay secure.',

    'screen.edit.layout.roles.title' => 'Roles',
    'screen.edit.layout.roles.descriptions' => 'A Role defines a set of tasks a user assigned the role is allowed to perform.',

    'screen.edit.layout.permissions.title' => 'Permissions',
    'screen.edit.layout.permissions.descriptions' => 'Allow the user to perform some actions that are not provided for by his roles',

    'screen.edit.toast.updated' => 'User was saved.',
    'screen.edit.toast.removed' => 'User was removed.',

    // UserListScreen
    'screen.list.title' => 'User Management',
    'screen.list.descriptions' => 'A comprehensive list of all registered users, including their profiles and privileges.',
    'screen.list.button.invite' => 'Invite',

    'screen.list.toast.save' => 'User was saved',
    'screen.list.toast.remove' => 'User was removed',

    // UserProfileScreen
    'screen.profile.title' => 'My Account',
    'screen.profile.descriptions' => 'Update your account details such as name, email address and password',

    'screen.profile.button.back' => 'Back to my account',
    'screen.profile.button.logout' => 'Logout',
    'screen.profile.button.save' => 'Save',

    'screen.profile.layout.information.title' => 'Profile Information',
    'screen.profile.layout.information.descriptions' => 'Update your account\'s profile information and email address.',

    'screen.profile.layout.password.title' => 'Update Password',
    'screen.profile.layout.password.descriptions' => 'Ensure your account is using a long, random password to stay secure.',

    'screen.profile.toast.saved' => 'Profile updated.',
    'screen.profile.toast.password_changed' => 'Password changed.',
];
