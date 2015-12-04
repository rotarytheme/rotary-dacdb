<?php

function rotary_dacdb_boardmembers_html( $atts ) {
	extract( shortcode_atts( array(
	'width' => '935',
	'height'  => '1100'
			), $atts ) );

	$options = get_option('rotary_dacdb');
	if ( 'yes' == $options['rotary_use_dacdb'] ) :
		$club = $options['rotary_dacdb_club'];
		$district = $options['rotary_dacdb_district'];
	
		ob_start();	
		?>
		
				<div style="margin: auto; width: <?php echo $width;?>px; height: <?php echo $height;?>px; text-align: center; overflow: hidden;">
	
			<iframe style="height: <?php echo $height;?>px; width: <?php echo $width;?>px;" src="http://www.ismyrotaryclub.org/Club/ClubLeaders.cfm?D=<?php echo $district;?>&amp;ClubID=<?php echo $club;?>&amp;xsl=http%3A%2F%2Fbellevuerun.com%2FCL14.xsl" width="<?php echo $width;?>" height="<?php echo $height;?>" scrolling="no">
			</iframe>
	
		</div>
		<?php 
		$output = ob_get_clean();
	endif;
	
return $output;

}
add_filter ( 'rotary_boardmembers', 'rotary_dacdb_boardmembers_html', 10, 1 );


