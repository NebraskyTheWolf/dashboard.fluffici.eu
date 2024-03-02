<?php

return [
    // UserListLayout.php
    'screen.user.name' => 'Uživatelské jméno',
    'screen.user.email' => 'Email',
    'screen.user.email.placeholder' => 'priklad@fluffici.eu',
    'screen.user.created' => 'Vytvořeno',
    'screen.user.updated_at' => 'Poslední aktualizace',
    'screen.user.actions.title' => 'Akce',
    'screen.user.actions.submenu.edit' => 'Upravit',
    'screen.user.actions.submenu.delete' => 'Smazat',
    'screen.user.actions.submenu.delete.confirm' => 'Jakmile je účet smazán, všechny jeho zdroje a data budou trvale smazány. Před smazáním účtu si stáhněte jakákoliv data nebo informace, které chcete zachovat.',

    // UserPasswordLayout.php
    'screen.user.password.title' => 'Heslo',
    'screen.user.password.placeholder.one' => 'Ponechte prázdné pro zachování současného hesla',
    'screen.user.password.placeholder.two' => 'Zadejte heslo, které má být nastaveno',

    // UserRoleLayout.php
    'screen.user.roles.title' => 'Role',
    'screen.user.roles.help' => 'Určete, ke kterým skupinám by tento účet měl patřit',

    // ProfilePasswordLayout.php
    'screen.user.old_password.title' => 'Současné heslo',
    'screen.user.old_password.help' => 'To je vaše heslo nastavené v tuto chvíli.',
    'screen.user.old_password.placeholder' => 'Zadejte staré heslo',

    'screen.user.new_password.title' => 'Nové heslo',
    'screen.user.new_password.placeholder' => 'Zadejte současné heslo',

    'screen.user.confirm_password.title' => 'Potvrzení nového hesla',
    'screen.user.confirm_password.help' => 'Dobré heslo má alespoň 15 znaků nebo alespoň 8 znaků, včetně čísla a malého písmena.',

    'screen.user.common_password.placeholder' => 'Zadejte současné heslo',

    // UserEditScreen
    'screen.edit.title' => 'Upravit uživatele',
    'screen.edit.title.create' => 'Vytvořit uživatele',
    'screen.edit.descriptions' => 'Profil a oprávnění uživatele, včetně přidružené role.',

    'screen.edit.button.remove' => 'Odebrat',
    'screen.edit.button.save' => 'Uložit',

    'screen.edit.layout.information.title' => 'Informace o profilu',
    'screen.edit.layout.information.descriptions' => 'Aktualizujte informace o profilu a e-mailové adrese vašeho účtu.',

    'screen.edit.layout.password.title' => 'Heslo',
    'screen.edit.layout.password.descriptions' => 'Ujistěte se, že váš účet používá dlouhé, náhodné heslo pro udržení bezpečnosti.',

    'screen.edit.layout.roles.title' => 'Role',
    'screen.edit.layout.roles.descriptions' => 'Role definuje soubor úkolů, které uživatel přiřazený roli smí provádět.',

    'screen.edit.layout.permissions.title' => 'Oprávnění',
    'screen.edit.layout.permissions.descriptions' => 'Povolte uživateli provádět některé akce, které nejsou poskytnuty jeho rolemi',

    'screen.edit.toast.updated' => 'Uživatel byl uložen.',
    'screen.edit.toast.removed' => 'Uživatel byl odstraněn.',

    // UserListScreen
    'screen.list.title' => 'Správa uživatelů',
    'screen.list.descriptions' => 'Komplexní seznam všech registrovaných uživatelů, včetně jejich profilů a oprávnění.',
    'screen.list.button.invite' => 'Pozvat',

    'screen.list.toast.save' => 'Uživatel byl uložen',
    'screen.list.toast.remove' => 'Uživatel byl odstraněn',

    // UserProfileScreen
    'screen.profile.title' => 'Můj účet',
    'screen.profile.descriptions' => 'Aktualizujte podrobnosti o vašem účtu, jako je jméno, e-mailová adresa a heslo',

    'screen.profile.button.back' => 'Zpět na můj účet',
    'screen.profile.button.logout' => 'Odhlásit se',
    'screen.profile.button.save' => 'Uložit',

    'screen.profile.layout.information.title' => 'Informace o profilu',
    'screen.profile.layout.information.descriptions' => 'Aktualizujte informace o profilu a e-mailové adrese vašeho účtu.',

    'screen.profile.layout.password.title' => 'Aktualizovat heslo',
    'screen.profile.layout.password.descriptions' => 'Ujistěte se, že váš účet používá dlouhé, náhodné heslo pro udržení bezpečnosti.',

    'screen.profile.toast.saved' => 'Profil byl aktualizován.',
    'screen.profile.toast.password_changed' => 'Heslo bylo změněno.',
];
