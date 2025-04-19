<?php

use PHPUnit\Framework\TestCase;
use App\Core\BaseController;
use App\Core\Exceptions\UnauthorizedException;
use App\Domain\Usuario\Entity;
use App\Domain\Usuario\Repository;

class BaseControllerTest extends TestCase
{
    private function mockHeaders(string $token = null): void
    {
        if (!function_exists('getallheaders')) {
            function getallheaders(): array
            {
                return $_SERVER['__MOCK_HEADERS'] ?? [];
            }
        }

        $_SERVER['__MOCK_HEADERS'] = [
            'Authorization' => $token ? 'Bearer ' . $token : ''
        ];
    }

    public function testUsuarioAutenticadoUsuarioNaoEncontrado()
    {
        $this->mockHeaders('token_valido');

        $mockTokenManager = $this->createMock(\App\Core\TokenManager::class);
        $mockTokenManager->method('validar')->willReturn(['id' => 99]);

        $mockRepo = $this->createMock(Repository::class);
        $mockRepo->method('buscarPorId')->with(99)->willReturn(null);

        $controller = new class($mockTokenManager, $mockRepo) extends BaseController {
            public function chamarAutenticado()
            {
                return $this->usuarioAutenticado();
            }
        };

        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('Token ausente ou inválido.');
        $controller->chamarAutenticado();
    }

    public function testJsonResponse(): void
    {
        $mockTokenManager = $this->createMock(\App\Core\TokenManager::class);
        $mockRepo = $this->createMock(Repository::class);

        $controller = new class($mockTokenManager, $mockRepo) extends BaseController {
            public function chamarJson(array $data, int $status = 200): string
            {
                ob_start();
                $this->json($data, $status);
                return ob_get_clean();
            }
        };

        $data = ['mensagem' => 'sucesso'];

        $output = $controller->chamarJson($data, 201);

        $this->assertJson($output);
        $this->assertEquals(json_encode($data), $output);
        $this->assertEquals(201, http_response_code());
    }

    public function testSuccessDefaultAndCustomMessage(): void
    {
        $mockTokenManager = $this->createMock(\App\Core\TokenManager::class);
        $mockRepo = $this->createMock(Repository::class);

        $controller = new class($mockTokenManager, $mockRepo) extends BaseController {
            public function chamarSuccess(string $msg = null): string
            {
                ob_start();
                $this->success($msg ?? 'Operação realizada com sucesso');
                return ob_get_clean();
            }
        };

        $outputPadrao = $controller->chamarSuccess();
        $this->assertJson($outputPadrao);
        $this->assertEquals(json_encode(['message' => 'Operação realizada com sucesso']), $outputPadrao);

        $outputCustom = $controller->chamarSuccess('Tudo certo');
        $this->assertJson($outputCustom);
        $this->assertEquals(json_encode(['message' => 'Tudo certo']), $outputCustom);
    }

    public function testErrorWithDefaultAndCustomStatus(): void
    {
        $mockTokenManager = $this->createMock(\App\Core\TokenManager::class);
        $mockRepo = $this->createMock(Repository::class);

        $controller = new class($mockTokenManager, $mockRepo) extends BaseController {
            public function chamarError(string $mensagem, int $status = 400): string
            {
                ob_start();
                $this->error($mensagem, $status);
                return ob_get_clean();
            }
        };

        $output400 = $controller->chamarError('Erro padrão');
        $this->assertJson($output400);
        $this->assertEquals(json_encode(['error' => 'Erro padrão']), $output400);
        $this->assertEquals(400, http_response_code());

        $output422 = $controller->chamarError('Erro de validação', 422);
        $this->assertJson($output422);
        $this->assertEquals(json_encode(['error' => 'Erro de validação']), $output422);
        $this->assertEquals(422, http_response_code());
    }

    public function testUsuarioAutenticadoTokenInvalido(): void
    {
        $this->mockHeaders('token_invalido');

        $mockTokenManager = $this->createMock(\App\Core\TokenManager::class);
        $mockTokenManager->method('validar')->willReturn(null); // simula token inválido

        $mockRepo = $this->createMock(Repository::class);

        $controller = new class($mockTokenManager, $mockRepo) extends BaseController {
            public function chamarAutenticado()
            {
                return $this->usuarioAutenticado();
            }
        };

        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('Token ausente ou inválido.');
        $controller->chamarAutenticado();
    }
}
