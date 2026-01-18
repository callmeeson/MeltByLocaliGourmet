<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="position: relative;">
	<label for="search-field" class="screen-reader-text" style="position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(1px, 1px, 1px, 1px);">
		<?php esc_html_e( 'Search for:', 'melt-custom' ); ?>
	</label>
	<div style="display: flex; gap: 0.5rem; align-items: stretch;">
		<input 
			type="search" 
			id="search-field"
			class="search-field" 
			placeholder="<?php echo esc_attr_x( 'Search luxury cakes...', 'placeholder', 'melt-custom' ); ?>" 
			value="<?php echo get_search_query(); ?>" 
			name="s" 
			style="flex: 1; padding: 0.75rem 1rem; border: 2px solid var(--border); font-family: var(--font-body); transition: border-color 0.3s ease;"
			onfocus="this.style.borderColor='var(--primary)'"
			onblur="this.style.borderColor='var(--border)'"
		/>
		<button 
			type="submit" 
			class="search-submit"
			style="padding: 0.75rem 1.5rem; background-color: var(--primary); color: white; border: none; font-family: var(--font-body); font-weight: 500; cursor: pointer; transition: background-color 0.3s ease; display: flex; align-items: center; gap: 0.5rem;"
			onmouseover="this.style.backgroundColor='var(--accent)'"
			onmouseout="this.style.backgroundColor='var(--primary)'"
		>
			<i data-lucide="search" style="width: 1.25rem; height: 1.25rem;"></i>
			<span><?php echo esc_html_x( 'Search', 'submit button', 'melt-custom' ); ?></span>
		</button>
	</div>
</form>
