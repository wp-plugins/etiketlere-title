<?php
/**
 * @package Etiketlere Title
 * @version 1.0
 */
/*
Plugin Name: Etiketlere Title
Plugin URI: http://www.seohocasi.com/
Description: Etiketlerin başlığında rakam ya da hiç bir şey yazmaması yerine SEO için etiketin kendisini yazan bir eklenti. <strong>Not:</strong> Eklentiyi aktif ettikten sonra normal etiket bulutu bileşeni yerine bu eklentinin bileşenini kullanmayı unutmayın.
Author: SEO Hocası
Version: 1.0
*/

function seohocasi_etiket_title($etiketler) {
	$etiketler = preg_replace('/<a href="(.*?)" rel="tag">(.*?)<\/a>/e', '\'<a href="$1" rel="tag" title="\'.htmlspecialchars(stripslashes(\'$2\')).\'">\'.stripslashes(\'$2\').\'</a>\'', $etiketler);
	return $etiketler;
}

function seohocasi_etiket_bulutu_widget($args) {
	extract($args);
	$ayarlar = get_option('seohocasi_etiket_bulutu');
	echo $before_widget;
	echo $before_title;
	echo $ayarlar['baslik'];
	echo $after_title;
	$etiketler = wp_tag_cloud(array('echo' => false));
	$etiketler = preg_replace('/<a href=\'(.*?)\' class=\'(.*?)\' title=\'(.*?)\' style=\'(.*?)\'>(.*?)<\/a>/e', '"<a href=\'$1\' class=\'$2\' title=\'".htmlspecialchars(stripslashes(\'$5\'))."\' style=\'$4\'>".stripslashes(\'$5\')."</a>"', $etiketler);
	echo $etiketler;
	echo $after_widget;
}

function seohocasi_etiket_bulutu_callback( $count ) {
	return sprintf( _n('%s picture', '%s pictures', $count), number_format_i18n( $count ) );
}

function seohocasi_etiket_bulutu_kaydet() {
	wp_register_sidebar_widget('seohocasi_etiket_bulutu_widget', __('Title Etiket Bulutu'), 'seohocasi_etiket_bulutu_widget', array('description' => 'Linklerdeki başlıklarda rakamlar yerine o etiketleri gösteren etiket bulutu'));

	register_widget_control('seohocasi_etiket_bulutu_widget', 'seohocasi_etiket_bulutu_widget_ayar');
}

function seohocasi_etiket_bulutu_widget_ayar() {
	$ayarlar = get_option('seohocasi_etiket_bulutu');
	if(!isset($ayarlar['baslik'])) {
		$ayarlar['baslik'] = 'Etiket Bulutu';
		update_option('seohocasi_etiket_bulutu', array('baslik' => 'Etiket Bulutu'));
	}
	
	$seb_widget_baslik = $ayarlar['baslik'];
	if(isset($_POST['seb_widget_baslik'])) {
		$seb_widget_baslik = $_POST['seb_widget_baslik'];
		update_option('seohocasi_etiket_bulutu', array('baslik' => $seb_widget_baslik));
	}
	echo '<label for="seb_widget_baslik">Başlık:</label><br/><input style="width: 100%" type=text name="seb_widget_baslik" id="seb_widget_baslik" value="'.$seb_widget_baslik.'" />';
}


add_action('widgets_init', 'seohocasi_etiket_bulutu_kaydet');
add_filter('the_tags', 'seohocasi_etiket_title');
?>