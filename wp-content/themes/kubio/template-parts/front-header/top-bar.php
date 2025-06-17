<div class="wp-block wp-block-kubio-navigation-top-bar  kubio-hide-on-mobile position-relative wp-block-kubio-navigation-top-bar__outer kubio-front-header__k__toCQddZ5xwe-outer kubio-local-639-outer d-flex align-items-lg-center align-items-md-center align-items-center" data-kubio="kubio/navigation-top-bar">
	<div class="background-wrapper">
		<div class="background-layer background-layer-media-container-lg"></div>
		<div class="background-layer background-layer-media-container-md"></div>
		<div class="background-layer background-layer-media-container"></div>
	</div>
	<div class="position-relative wp-block-kubio-navigation-top-bar__inner kubio-front-header__k__toCQddZ5xwe-inner kubio-local-639-inner h-section-grid-container h-section-boxed-container">
		<div class="wp-block wp-block-kubio-row  position-relative wp-block-kubio-row__container kubio-front-header__k__RIVZr4--0St-container kubio-local-640-container gutters-row-lg-0 gutters-row-v-lg-0 gutters-row-md-0 gutters-row-v-md-0 gutters-row-0 gutters-row-v-0" data-kubio="kubio/row">
			<div class="background-wrapper">
				<div class="background-layer background-layer-media-container-lg"></div>
				<div class="background-layer background-layer-media-container-md"></div>
				<div class="background-layer background-layer-media-container"></div>
			</div>
			<div class="position-relative wp-block-kubio-row__inner kubio-front-header__k__RIVZr4--0St-inner kubio-local-640-inner h-row align-items-lg-stretch align-items-md-stretch align-items-stretch justify-content-lg-center justify-content-md-center justify-content-center gutters-col-lg-0 gutters-col-v-lg-0 gutters-col-md-0 gutters-col-v-md-0 gutters-col-0 gutters-col-v-0">
				<!-- Custom Top Navbar -->
				<ul class="custom-top-navbar d-flex align-items-center" style="list-style:none;margin:0;padding:0;gap:20px;">
					<li><a href="/contact" class="top-navbar-link">Contact</a></li>
					<li style="border-left:1px solid #ccc;height:18px;"></li>
					<li><a href="/mentions-legales" class="top-navbar-link">Mentions légales</a></li>
					<li style="border-left:1px solid #ccc;height:18px;"></li>
					<li><a href="/protection-des-donnees" class="top-navbar-link">Protection des données</a></li>
					<li style="border-left:1px solid #ccc;height:18px;"></li>
					<li>
						<div class="top-navbar-language-switcher">
							<?php if (function_exists('pll_the_languages')) {
								$languages = pll_the_languages(array(
									'show_flags' => 1,
									'show_names' => 1,
									'dropdown' => 0,
									'hide_if_no_translation' => 0,
									'display_names_as' => 'name',
									'raw' => 1
								));
								if ($languages) {
									$onerror = htmlspecialchars("this.style.display='none';this.insertAdjacentHTML('afterend','<span class=\\'flag-fallback\\'>🏳️</span>')", ENT_QUOTES);
									echo '<div class="language-dropdown" tabindex="0">';
									echo '<button class="language-dropdown-toggle" type="button" aria-haspopup="listbox" aria-expanded="false" style="font-size:0.98em;font-weight:500;">';
									foreach ($languages as $lang) {
										if ($lang['current_lang']) {
											if (!empty($lang['flag'])) {
												echo '<img src="' . esc_url($lang['flag']) . '" alt="' . esc_attr($lang['name']) . ' flag" class="flag-icon" onerror="' . $onerror . '"> ';
											} else {
												echo '<span class="flag-fallback">🏳️</span> ';
											}
											echo '<span class="language-name">' . esc_html($lang['name']) . '</span>';
											break;
										}
									}
									echo '<svg class="dropdown-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z" fill="currentColor"/></svg>';
									echo '</button>';
									echo '<ul class="language-dropdown-menu" tabindex="-1" role="listbox">';
									foreach ($languages as $lang) {
										$active_class = $lang['current_lang'] ? 'active' : '';
										echo '<li class="' . $active_class . '" role="option">';
										if ($lang['current_lang']) {
											if (!empty($lang['flag'])) {
												echo '<img src="' . esc_url($lang['flag']) . '" alt="' . esc_attr($lang['name']) . ' flag" class="flag-icon" onerror="' . $onerror . '"> ';
											} else {
												echo '<span class="flag-fallback">🏳️</span> ';
											}
											echo '<span class="language-name">' . esc_html($lang['name']) . '</span>';
										} else {
											echo '<a href="' . esc_url($lang['url']) . '" class="language-option">';
											if (!empty($lang['flag'])) {
												echo '<img src="' . esc_url($lang['flag']) . '" alt="' . esc_attr($lang['name']) . ' flag" class="flag-icon" onerror="' . $onerror . '"> ';
											} else {
												echo '<span class="flag-fallback">🏳️</span> ';
											}
											echo '<span class="language-name">' . esc_html($lang['name']) . '</span>';
											echo '</a>';
										}
										echo '</li>';
									}
									echo '</ul>';
									echo '</div>';
								}
							} ?>
						</div>
					</li>
				</ul>
				<!-- End Custom Top Navbar -->
			</div>
		</div>
	</div>
</div>
