<?php

return [
    // UserListLayout.php
    'screen.user.name' => 'Uživatelské jméno',
    'screen.user.email' => 'E-mailová adresa',
    'screen.user.email.placeholder' => 'příklad@fluffici.eu',
    'screen.user.created' => 'Vytvořeno dne',
    'screen.user.updated_at' => 'Poslední aktualizace',
    'screen.user.actions.title' => 'Akce',
    'screen.user.actions.submenu.edit' => 'Upravit',
    'screen.user.actions.submenu.delete' => 'Smazat',
    'screen.user.actions.submenu.delete.confirm' => 'Jakmile je účet odstraněn, všechny jeho zdroje a data budou trvale smazány. Před odstraněním účtu si prosím stáhněte všechna data nebo informace, které si přejete uchovat.',

    // UserPasswordLayout.php
    'screen.user.password.title' => 'Heslo',
    'screen.user.password.placeholder.one' => 'Ponechte prázdné pro zachování aktuálního hesla',
    'screen.user.password.placeholder.two' => 'Zadejte heslo k nastavení',

    // UserRoleLayout.php
    'screen.user.roles.title' => 'Role',
    'screen.user.roles.help' => 'Určete, ke kterým skupinám by měl tento účet patřit',

    // ProfilePasswordLayout.php
    'screen.user.old_password.title' => 'Stávající heslo',
    'screen.user.old_password.help' => 'Toto je vaše současné heslo.',
    'screen.user.old_password.placeholder' => 'Zadejte stávající heslo',

    'screen.user.new_password.title' => 'Nové heslo',
    'screen.user.new_password.placeholder' => 'Zadejte nové heslo',

    'screen.user.confirm_password.title' => 'Potvrzení nového hesla',
    'screen.user.confirm_password.help' => 'Dobré heslo má alespoň 15 znaků nebo alespoň 8 znaků, včetně čísla a malého písmena.',

    'screen.user.common_password.placeholder' => 'Zadejte aktuální heslo',

    // UserEditScreen
    'screen.edit.title' => 'Upravit uživatele',
    'screen.edit.title.create' => 'Vytvořit uživatele',
    'screen.edit.descriptions' => 'Uživatelský profil a oprávnění, včetně přidružené role.',

    'screen.edit.button.remove' => 'Odstranit',
    'screen.edit.button.save' => 'Uložit',

    'screen.edit.layout.information.title' => 'Informace o profilu',
    'screen.edit.layout.information.descriptions' => 'Aktualizujte informace o profilu a e-mailovou adresu vašeho účtu.',

    'screen.edit.layout.password.title' => 'Heslo',
    'screen.edit.layout.password.descriptions' => 'Ujistěte se, že váš účet používá dlouhé, náhodné heslo, aby zůstal bezpečný.',

    'screen.edit.layout.roles.title' => 'Role',
    'screen.edit.layout.roles.descriptions' => 'Role definuje sadu úkolů, které může uživatel vykonávat přiřazený roli.',

    'screen.edit.layout.permissions.title' => 'Oprávnění',
    'screen.edit.layout.permissions.descriptions' => 'Umožnit uživateli vykonávat některé akce, které nejsou povoleny jeho rolí.',

    'screen.edit.toast.updated' => 'Uživatel byl uložen.',
    'screen.edit.toast.removed' => 'Uživatel byl odstraněn.',

    // UserListScreen
    'screen.list.title' => 'Správa uživatelů',
    'screen.list.descriptions' => 'Komplexní seznam všech registrovaných uživatelů včetně jejich profilů a výsad.',
    'screen.list.button.invite' => 'Pozvat',

    'screen.list.toast.save' => 'Uživatel byl uložen',
    'screen.list.toast.remove' => 'Uživatel byl odstraněn',

    // UserProfileScreen
    'screen.profile.title' => 'Můj účet',
    'screen.profile.descriptions' => 'Aktualizujte údaje o účtu jako jméno, e-mailovou adresu a heslo',

    'screen.profile.button.back' => 'Zpět na můj účet',
    'screen.profile.button.logout' => 'Odhlásit se',
    'screen.profile.button.save' => 'Uložit',

    'screen.profile.layout.information.title' => 'Informace o profilu',
    'screen.profile.layout.information.descriptions' => 'Aktualizujte informace o profilu a e-mailovou adresu vašeho účtu.',

    'screen.profile.layout.password.title' => 'Aktualizovat heslo',
    'screen.profile.layout.password.descriptions' => 'Ujistěte se, že váš účet používá dlouhé, náhodné heslo, aby zůstal bezpečný.',

    'screen.profile.toast.saved' => 'Profil byl aktualizován.',
    'screen.profile.toast.password_changed' => 'Heslo bylo změněno.',
];
