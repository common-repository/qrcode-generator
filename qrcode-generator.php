<?php
/*
  Plugin Name: QR Code Generator
  Plugin URI: http://brainesia.com/plugins/qrcode-generator
  Description: A plugin to generate QR code for your WordPress posts/pages
  Author: Brainesia
  Version: 1.1
  Author URI: http://brainesia.com/
 */

function btqrcodegenerator( $width = '100', $height = '100', $url = null, $choe = 'UTF-8', $type = 'qr') {

   
    if(empty($url)){
        $url = get_permalink(); 
    }
    
    $title = get_the_title();

    echo '<img src="http://chart.apis.google.com/chart?cht=' . $type . '&chs=' . $width . 'x' . $height . '&chl=' . htmlspecialchars($url) . '&choe=' . $choe . '" alt="' . $title . '" />';
}


function widget_qrCode($args) {
    extract($args);

    $options = get_option("widget_qrCode");
    if (!is_array($options)) {
        $options = array(
            'title' => '',
            'width' => '128',
            'height' => '128',
            'uri' => null
        );
    }

    echo $before_widget;
    echo $before_title;
    echo $options['title'];
    echo $after_title;

  
    btqrcodegenerator( $options['height'], $options['width'], $options['uri'] );
    echo $after_widget;
}


function qrcodegen_control() {
    $options = get_option("widget_qrCode");
    if (!is_array($options)) {
        $options = array(
            'title' => 'QR Code',
            'width' => '128',
            'height' => '128',
            'uri' => null
        );
    }

    if (isset($_POST['qrCode-submit'])) {
        $options['title'] = htmlspecialchars($_POST['widgetTitle']);
        $options['height'] = htmlspecialchars($_POST['widgetHeight']);
        $options['width'] = htmlspecialchars($_POST['widgetWidth']);
        $options['uri'] = htmlspecialchars($_POST['widgetUri']);
        update_option("widget_qrCode", $options);
    }
    ?>
    <p>
        <label for="widgetTitle">Widget Title: </label>
        <input type="text" id="widgetTitle" name="widgetTitle" value="<?php echo $options['title']; ?>" />
    </p>
    <p>
        <label for="widgetHeight">Height: </label>
        <input type="text" id="widgetHeight" name="widgetHeight" value="<?php echo $options['height']; ?>" />
    </p>
    <p>
        <label for="widgetWidtht">Width: </label>
        <input type="text" id="widgetWidth" name="widgetWidth" value="<?php echo $options['width']; ?>" />
    </p>
    <p>
        <label for="widgetUri">Url:  </label> *optional
        <input type="text" id="widgetUri" name="widgetUri" value="<?php echo $options['uri']; ?>" /><br/>
        <small>The plugin will generate qr code for viewed page. If you want to display qr code for your desired URL, put the URL above. </small>
    </p>
    <p>  
        <input type="hidden" id="qrCode-Submit" name="qrCode-submit" value="1" />
    </p>
    <?php
}


function qrcodegen_init() {
    wp_register_sidebar_widget('qr_code_widget', 'QR Code Generator', 'widget_qrCode', null);
    wp_register_widget_control('qr_code_widget', 'QR Code Generator', 'qrcodegen_control', null, null);
}

add_action("plugins_loaded", "qrcodegen_init");
?>
