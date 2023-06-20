<div id="clickable-dropdown-toggle" class="selected d-flex" justifyContent="center" alignItems="center" position="relative">
	<span class="flag <?php if($_SESSION['language'] == ''){echo Config::site['language'];}else{echo $_SESSION['language'];} ?>"></span>
</div>
<div id="clickable-dropdown-content" class="other-languages d-flex" flexDirection="column" position="absolute">
	<form action="<?php echo Config::call['Controllers'] . "/selectLanguage"; ?>" method="POST">
    <button name="en-us" class="option d-flex" justifyContent="center" style="background: transparent;border: none;height: 42px;">
		<span class="flag en-us"></span>
	</button>
	<button name="br-pt" class="option d-flex" justifyContent="center" style="background: transparent;border: none;height: 42px;">
		<span class="flag br-pt"></span>
	</button>
	<button name="tr" class="option d-flex" justifyContent="center" style="background: transparent;border: none;height: 42px;">
		<span class="flag tr"></span>
	</button>
	<button name="de" class="option d-flex" justifyContent="center" style="background: transparent;border: none;height: 42px;">
		<span class="flag de"></span>
	</button>
	<button name="es" class="option d-flex" justifyContent="center" style="background: transparent;border: none;height: 42px;">
		<span class="flag es"></span>
	</button>
	<button name="fi" class="option d-flex" justifyContent="center" style="background: transparent;border: none;height: 42px;">
		<span class="flag fi"></span>
	</button>
	<button name="fr" class="option d-flex" justifyContent="center" style="background: transparent;border: none;height: 42px;">
		<span class="flag fr"></span>
	</button>
	<button name="it" class="option d-flex" justifyContent="center" style="background: transparent;border: none;height: 42px;">
		<span class="flag it"></span>
	</button>
	<button name="nl" class="option d-flex" justifyContent="center" style="background: transparent;border: none;height: 42px;">
		<span class="flag nl"></span>
	</button>
</form>
</div>
