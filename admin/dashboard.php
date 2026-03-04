<?php

function wpsd_dashboard() {
    
?>

<div class="wrap wpsd-app">

<div class="wpsd-header">

<div class="wpsd-brand">
<span class="wpsd-logo">
   <img src="<?php echo plugin_dir_url(dirname(__FILE__)) . 'assets/image/logo.jpg'; ?>">
</span>
<div class="wpsd-title">
<h1>WP Site Doctor</h1>
<p>Health, speed and cleanup for WordPress</p>
</div>
</div>

<div class="wpsd-version">
Compatible with WordPress 6.x – 7.x
</div>

</div>


<div class="wpsd-grid">

<div class="wpsd-card">
<span class="wpsd-card-title">Plugins</span>
<div class="wpsd-number"><?php wpsd_plugin_count(); ?></div>
<span class="wpsd-label">Installed</span>
</div>

<div class="wpsd-card">
<span class="wpsd-card-title">WordPress</span>
<div class="wpsd-number"><?php echo get_bloginfo('version'); ?></div>
<span class="wpsd-label">Version</span>
</div>

<div class="wpsd-card">
<span class="wpsd-card-title">Active Theme</span>
<div class="wpsd-number"><?php echo wp_get_theme()->get('Name'); ?></div>
<span class="wpsd-label">Current Theme</span>
</div>

<div class="wpsd-card">
<!-- <span class="wpsd-card-title">Health Score</span>
<div class="wpsd-number"><?php echo wpsd_health_score(); ?> /100 </div> -->
 <span class="wpsd-card-title" style="font-weight:bold; display:block; margin-bottom:10px;">Health Score</span>
    <?php 
        $score = wpsd_health_score(); 

        // Set color and wording based on score
        if ($score >= 90) {
            $color = "#4CAF50"; // green
            $text = "Excellent";
        } elseif ($score >= 70) {
            $color = "#FFC107"; // amber
            $text = "Good";
        } elseif ($score >= 50) {
            $color = "#FF9800"; // orange
            $text = "Fair";
        } else {
            $color = "#F44336"; // red
            $text = "Poor";
        }
    ?>
    <div class="wpsd-number" style="font-size:28px; font-weight:bold; color:<?php echo $color; ?>;">
        <?php echo $score; ?> /100
    </div>
    <span class="wpsd-label" style="display:block; margin-top:5px; font-weight:bold; color:<?php echo $color; ?>;">
        <?php echo $text; ?>
    </span>
</div>
</div>

<div class="wpsd-actions">

<button id="wpsd-scan" class="wpsd-btn-primary">
Run Health Scan
</button>
<button id="wpsd-fix" class="wpsd-btn-primary">
⚡ Fix Issues Automatically
</button>
<button id="wpsd-clean" class="wpsd-btn">
Clean Cache / Temp Data
</button>
</div>
</div>
<div id="wpsd-result"></div>   
<div id="wpsd-fix-result"></div> 
<?php
}