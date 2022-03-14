<?php
/**
 * Exit if accessed directly
 */
defined( 'ABSPATH' ) || exit( 'illegal access!' );
function woo_multi_all_category($options,$name_field)
{
    $html='';
    $parent_cat_arg = array('hide_empty' => false, 'parent' => 0);
    $parent_categories = get_terms('product_cat', $parent_cat_arg);
    foreach ($parent_categories as $category) {
        $select='';
        if(isset($_POST[$name_field])) {
            foreach ($_POST[$name_field] as $opTerm){
                if($opTerm == $category->term_id)
                    $select = "selected";
            }
        }
        else if(isset($options[$name_field])) {
            foreach (explode(',',$options[$name_field]) as $opTerm){
                if($opTerm == $category->term_id)
                    $select = "selected";
            }
        }
        $html .='<option value="' . $category->term_id . '" ' . $select . '>' . $category->name . '</option>'; //Parent Category
        $child_arg = array('hide_empty' => false, 'parent' => $category->term_id);
        $child_cat = get_terms('product_cat', $child_arg);
        foreach ($child_cat as $child_term) {
            $select_1='';
            if(isset($_POST[$name_field])) {
                foreach ($_POST[$name_field] as $opTerm){
                    if($opTerm == $child_term->term_id)
                        $select_1 = "selected";
                }
            }
            else if(isset($options[$name_field])) {
                foreach (explode(',',$options[$name_field]) as $opTerm){
                    if($opTerm == $child_term->term_id)
                        $select_1 = "selected";
                }
            }
            $html .= '<option value="' . $child_term->term_id . '" ' . $select_1 . '>-' . $child_term->name . '</option>'; //Parent Category
            $child2_arg = array('hide_empty' => false, 'parent' => $child_term->term_id);
            $child2_cat = get_terms('product_cat', $child2_arg);
            foreach ($child2_cat as $child2_term) {
                $select_2='';
                if(isset($_POST[$name_field])) {
                    foreach ($_POST[$name_field] as $opTerm){
                        if($opTerm == $child2_term->term_id)
                            $select_2 = "selected";
                    }
                }
                else if(isset($options[$name_field])) {
                    foreach (explode(',',$options[$name_field]) as $opTerm){
                        if($opTerm == $child2_term->term_id)
                            $select_2 = "selected";
                    }
                }
                $html .= '<option value="' . $child2_term->term_id . '" ' . $select_2 . '>--' . $child2_term->name . '</option>'; //Parent Category
            }
        }
    }
    return $html;
}

?>
<form method="post">
<table class="form-table">
    <tbody>
    <tr>
        <th scope="row">
            <label for="product_create">
                <?php _e('ایجاد محصول با آپلود عکس در رسانه','solowoo')?>
            </label>
        </th>
        <td>
            <select class="" name="product_create">
                <option value="1" <?php if(isset($_POST['product_create'])&&$_POST['product_create']=='1'||isset($options['product_create'])&&$options['product_create']=='1') echo 'selected'?>><?php _e('فعال','solowoo') ?></option>
                <option value="0" <?php if(isset($_POST['product_create'])&&$_POST['product_create']=='0'||isset($options['product_create'])&&$options['product_create']=='0') echo 'selected'?>><?php _e('غیرفعال','solowoo') ?></option>
            </select>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php _e('نام محصول','woocommerce')?></th>
        <td>
            <input class="" name="product_title" value=" <?php if(isset($_POST['product_title'])) echo $_POST['product_title']; elseif($options['product_title']){echo $options['product_title'];} ?>"/>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php _e('دسته بندی محصول','solowoo');?></th>
        <td>
            <select class="" name="product_categories[]" multiple>
                <?php echo woo_multi_all_category($options,"product_categories");?>
            </select>
        </td>
    </tr>
    <tr>
        <th scope="row"><?php _e('برچسب','solowoo')?></th>
        <td>
            <input class="" name="product_tags" value=" <?php if(isset($_POST['product_tags'])) echo $_POST['product_tags']; elseif(isset($options['product_tags'])){echo $options['product_tags'];} ?>"/>
            <span>برچسب ها را با کاما از هم جداکنید.</span>
        </td>
    </tr>
    </tbody>
</table>
    <?php
    wp_nonce_field( 'woo_save_multi_nonce', 'woo_multi_nonce' );
    submit_button( __('به روزرسانی','solowoo'), 'primary', 'woo_save_multi', true );
    ?>
</form>
