<?php
$paginaAtual = basename($_SERVER['PHP_SELF']);
?>

<!-- OVERLAY -->
<div id="overlay"
    onclick="toggleSidebar()"
    class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden">
</div>

<!-- SIDEBAR -->
<aside id="sidebar"
    class="fixed top-0 left-0 w-64 h-screen overflow-y-auto bg-white shadow-md flex flex-col justify-between
           transform -translate-x-full md:translate-x-0 transition-transform duration-300 z-50">
    <!-- 🔝 PARTE DE CIMA -->
    <div>
        <!-- Logo -->
        <div class="h-[78px] border-b flex flex-col items-center justify-center">
            <h1 class="text-lg font-bold text-primary leading-none">
                E-Treinamento
            </h1>

            <img src="https://udlog.online/imagens/udlog.png"
                alt="UDLOG"
                class="mt-2 h-6 object-contain">
        </div>
    </div>

    <!-- Menu -->
    <nav class="flex-1 p-4 space-y-2 text-sm">

        <?php
        function menuItem($href, $label, $paginaAtual)
        {
            $active = basename($href) == $paginaAtual;
            return "
                <a href='$href'
                    class='block px-4 py-2 rounded-lg " .
                ($active
                    ? "bg-gray-100 text-primary font-semibold"
                    : "hover:bg-gray-100 text-gray-700") .
                "'>
                    $label
                </a>";
        }

        echo menuItem('/etreinamento/layout/gerenciarColaboradores.php', 'Colaboradores', $paginaAtual);
        echo menuItem('/etreinamento/layout/gerenciarEmpresas.php', 'Empresas', $paginaAtual);
        echo menuItem('/etreinamento/layout/gerenciarDepartamentos.php', 'Departamentos', $paginaAtual);
        echo menuItem('/etreinamento/layout/gerenciarTreinamento.php', 'Treinamentos', $paginaAtual);
        echo menuItem('/etreinamento/layout/gerenciarInstrutores.php', 'Instrutores', $paginaAtual);
        echo menuItem('/etreinamento/layout/gerenciarConta.php', 'Minha Conta', $paginaAtual);
        echo menuItem('/etreinamento/layout/cadastrarUsuario.php', 'Usuários', $paginaAtual);
        echo menuItem('/etreinamento/index2.php', 'Dashboard', $paginaAtual);
        ?>

    </nav>
    </div>

    <!-- 🔻 LOGOUT -->
    <div class="p-4 border-t">
        <a href="/etreinamento/src/actions/logout.php"
            class="block w-full text-center bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
            Sair
        </a>
    </div>

</aside>

<!-- SCRIPT -->
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
</script>