<?php
$value = (isset($_GET['s']) && get_query_var('s') !== '') ? get_query_var('s'): '';
?>
<form role="search" method="get" id="searchform" action="<?php echo home_url( '/blog/' ); ?>">
    <div id="searchdiv" ><label class="assistive-text" for="s">Search</label>
        <input id="s" type="text" name="s" placeholder="Search" value="<?php echo $value; ?>" >
        <input title="Search" type="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'nest' ); ?>" />
    </div>
</form>
