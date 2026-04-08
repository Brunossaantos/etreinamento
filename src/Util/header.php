<header class="bg-white shadow-sm px-4 sm:px-6 py-4 flex items-center justify-between">

    <!-- ESQUERDA -->
    <div class="flex items-center gap-3">

        <!-- BOTÃO MOBILE -->
        <button onclick="toggleSidebar()"
            class="md:hidden bg-gray-100 p-2 rounded-lg">
            ☰
        </button>

        <!-- TÍTULO -->
        <div>
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800">
                <?= $tituloPagina ?? '' ?>
            </h2>
            <p class="text-xs text-gray-500 hidden sm:block">
                Bem-vindo ao sistema
            </p>
        </div>

    </div>

    <!-- DIREITA (USUÁRIO) -->
    <div class="flex items-center gap-2 sm:gap-3 max-w-[180px] sm:max-w-none">

        <!-- Avatar -->
        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-primary text-white flex items-center justify-center rounded-full font-semibold">
            <?= strtoupper(substr($_SESSION["nome"], 0, 1)) ?>
        </div>

        <!-- Info -->
        <div class="text-sm hidden sm:block">
            <p class="font-medium text-gray-800 truncate max-w-[120px]">
                <?= $_SESSION["nome"] ?? 'Usuário' ?>
            </p>
            <p class="text-xs text-gray-500 flex items-center gap-1">
                <span class="w-2 h-2 bg-green-500 rounded-full inline-block"></span>
                Online
            </p>
        </div>

    </div>

</header>