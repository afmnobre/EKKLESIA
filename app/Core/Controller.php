<?php

namespace App\Core;

class Controller
{
	public function view($view, $data = [])
	{
		extract($data);

		require __DIR__ . '/../Views/layouts/header.php';
		require __DIR__ . '/../Views/layouts/sidebar.php';

		// O CSS ".content" com margin-left vai empurrar tudo para a direita do sidebar
		echo '<div class="content">';
		require __DIR__ . '/../Views/paginas/' . $view . '.php';
		echo '</div>';

		require __DIR__ . '/../Views/layouts/footer.php';
	}

    public function viewAuth($view, $data = [])
    {
        extract($data);

        require __DIR__ . '/../Views/layouts/header.php';
        require __DIR__ . '/../Views/' . $view . '.php';
        require __DIR__ . '/../Views/layouts/footer.php';
    }

	public function rawview($view, $data = [])
	{
		extract($data);

		// Carrega apenas o header (CSS/Meta) e o rodapé (JS)
		// Sem o sidebar e sem a div .content para ocupar a tela toda no celular
		require __DIR__ . '/../Views/layouts/header.php';

		// Carrega a página específica
		require __DIR__ . '/../Views/paginas/' . $view . '.php';

		require __DIR__ . '/../Views/layouts/footer.php';
	}

}


