<?php
	/**
	 * @accept $data
	 */
?>
<div class="categorydiv">
	<div class="tabs-panel">
		<div class="categorychecklist form-no-clear">
			<ul>
				<?php foreach( $data->compatibility as $term ): ?>
				<li class="selectit">
					<?php
						$locale = $data->locale;
						$title = isset( $term->translations->$locale->name ) && $term->translations->$locale->name <> '' ? $term->translations->$locale->name : $term->name;
					?>
					<label><input type="checkbox" name="compatibility[]" value="<?php echo esc_attr( $term->term_id ); ?>"> <?php printf( '%s (%d)', $title, $term->count ); ?> </label>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>					
</div>