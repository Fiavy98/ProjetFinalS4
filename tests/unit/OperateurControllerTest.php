<?php

use App\Controllers\operateurControlleur;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class OperateurControllerTest extends CIUnitTestCase
{
    public function testProfilRendersViewWithoutNamespaceError(): void
    {
        $controller = new operateurControlleur();

        $result = $controller->profil();

        $this->assertIsString($result);
        $this->assertStringContainsString('Profil opérateur', $result);
    }
}
