<footer class="container mx-auto p-4 text-sm border-t flex justify-between items-center">
    <div>Thomas More - © {{ date('Y') }}</div>
    @env('local')
        <div class="text-gray-400 text-xs text-center cursor-pointer ">
            <span class="sm:hidden">&lt; 640</span>
            <span class="hidden sm:block md:hidden">SM | 640 - 768</span>
            <span class="hidden md:block lg:hidden">MD | 768 - 1024</span>
            <span class="hidden lg:block xl:hidden">LG | 1024 - 1280</span>
            <span class="hidden xl:block 2xl:hidden">XL | 1280 - 1536</span>
            <span class="hidden 2xl:block">2XL |  &gt; 1536</span>
        </div>
    @endenv
    <div>Build with Laravel {{ app()->version() }}</div>
</footer>
