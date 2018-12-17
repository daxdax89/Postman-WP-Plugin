<?php
    $redirects = get_option('iv_dev_redirects');
    if (!empty($redirects)) {
        $redirects = json_decode($redirects, true);
    }
    if (!is_array($redirects)) {
        $redirects = array();
    }
    $args = array(
        'posts_per_page'   => -1,
        'post_type'        => ['post', 'page'],
        'post_status' => 'publish'
    );
    $query = new WP_Query( $args );
    $res = [];
    $posts = $query->posts;
    echo "<div id='destination_holder' style='display:none'><select name='destination_selector[]' class='ui fluid normal dropdown'>";
    if (!empty($posts)) {
        foreach($posts as $post) {
            if (empty($post->post_title)) {
                $title= 'Unnamed post with ID: '.$post->ID;
            } else {
                $title = $post->post_title;
            }
            echo "<option value='".$post->ID."'>".$title.'</option>';
        }
        
    }
    echo "</select></div>";
?>
<div class="wrap">
    <h1>Redirection maker</h1>
    <div id="post-body-content">
    <form name="redirects" id="form_redirects">
        <table class="widefat">
        <thead>
            <tr>
                <th class="row-title">Referer contains</th>
                <th>Redirect to</th>
                <th>Options</th>
            </tr>
        </thead>
        <tbody id="container_body">
        <?php
        foreach ($redirects as $k=> $r) {
            echo '<tr><td><input type="text" Placeholder="Referer URL contains" class=" other_text large-text" value="'.$k.'" name="url_contains[]"></td>';
            echo "<td><select name='destination_selector[]' class='ui fluid normal dropdown initial_select'>";
            if (!empty($posts)) {
                foreach($posts as $post) {
                    if (empty($post->post_title)) {
                        $title= 'Unnamed post with ID: '.$post->ID;
                    } else {
                        $title = $post->post_title;
                    }
                    if ($post->ID == $r) {
                        $adder = 'selected';
                    } else {
                        $adder = '';
                    }
                    echo "<option value='".$post->ID."' ".$adder.">".$title.'</option>';
                }
            }
            echo "</select></td><td style='text-align:center'><div class='button-secondary delete_row' >Remove me!</div></td></tr>";
        }
?>
        
        
        </tbody>
        <tfoot>
        <tr>
            <th class="row-title" colspan=2><div class="button-primary" id="add_pair"  >Add a new pair</div></th>
            <th class="row-title" style="text-align: right"><div class="button-primary" id="save_form">Save!</div></th>
        </tr>
        </tfoot>
        </table>
	</form>						

    </div>
						
</div>

				