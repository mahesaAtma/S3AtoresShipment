<style>
    .pagination > li > a
    {
        background-color: white;
        color: #5A4181;
    }

    .pagination > li > a:focus,
    .pagination > li > a:hover,
    .pagination > li > span:focus,
    .pagination > li > span:hover
    {
        color: #5a5a5a;
        background-color: #eee;
        border-color: #ddd;
    }

    .pagination > .active > a
    {
        color: white;
        background-color: #2C4074 !Important;
        border: solid 1px #2C4074 !Important;
    }

    .pagination > .active > a:hover
    {
        background-color: #152D6B !Important;
        border: solid 1px #152D6B;
    }
</style>

<?php
    if (isset($linkParent) && isset($totalPagination) && (int) $totalPagination > 0) {
        $pagePerItem = 10;
        $maxPage = ceil(($totalPagination / $pagePerItem));
        $currentPage = 1;
        if (isset($_GET['pageNumber']) && ((int) $_GET['pageNumber']) <= $maxPage && ((int) $_GET['pageNumber'] > 0)) {
            $currentPage = (int) $_GET['pageNumber'];
        }
?>
        <nav aria-label="Page navigation example" class="pagination-component mt-2 mb-4" data-current-page="<?= $currentPage ?>">
            <ul class="pagination">
                <?php
                    if ($currentPage > 1) {
                ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= $linkParent . '&pageNumber=' . ($currentPage - 1) ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>
                <?php
                    }
                ?>

                <?php
                    if ($currentPage - 3 > -1) {
                        echo "<li class='page-item'><a class='page-link' href='" . $linkParent . '&pageNumber=' . 1 . "'>1</a></li>";
                        echo "<li class='page-item'><a class='page-link'>...</a></li>";
                    }

                    for ($pageIndex=-1; $pageIndex < 2; $pageIndex++) { 
                        $currentPageIndex = $currentPage + $pageIndex;
                        if ($currentPageIndex > $maxPage) {
                            break;
                        }
                        if ($currentPageIndex <= 0) {
                            continue;
                        }

                        echo "<li id='pageIndex$currentPageIndex' class='page-item'><a class='page-link' href='" . $linkParent . '&pageNumber=' . $currentPageIndex . "'>$currentPageIndex</a></li>";
                    }

                    if ($maxPage >= $currentPage + 2) {
                        echo "<li class='page-item'><a class='page-link'>...</a></li>";
                        echo "<li class='page-item'><a class='page-link' href='" . $linkParent . '&pageNumber=' . $maxPage . "'>$maxPage</a></li>";
                    }
                ?>

                <?php
                    if ($currentPage < $maxPage) {
                ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= $linkParent . '&pageNumber=' . ($currentPage + 1) ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                <?php
                    }
                ?>
            </ul>
        </nav>
<?php
    }else{
        echo "<a class='mt-2 mb-4' style='color:red;'>Terjadi Kesalahan</a>";
    }
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        currentPageNumber = $('.pagination-component').attr('data-current-page');
        $('.pagination-component').removeClass('active');

        activePageElement = $('.pagination-component .page-item a.page-link').filter((_,el) => el.text == currentPageNumber).get();
        $('.pagination-component #pageIndex' + currentPageNumber).addClass('active');
        console.log(currentPageNumber);
        
    });
</script>