<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<?php $core = Core::I(); ?>

<html>
	<head>
		<title><?php print $core->conf->DEF_TITLE; ?></title>
		<link rel="stylesheet" type="text/css" href="/temp/<?php print $core->conf->DEFAULT_THEME; ?>/css/css.css">
		<script type="text/javascript" src="/temp/<?php print $core->conf->DEFAULT_THEME; ?>/js/jquery.js"></script>
		<style>		
			body{
				background-image: url('/temp/<?php print $core->conf->DEFAULT_THEME; ?>/img/bcg.png');
				background-size: 2px;
				font-family: arial;
			}
		</style>
	</head>
	<body>
	
		<div class="menu">
			<?php 
				$back = urldecode(http_build_query(array_merge($_GET,array('back'=>$core->url))));
				if(!$core->user->logged){ ?>
				
					<a class="link" href="/auth?<?php print $back ?>"><?php print AUTH ?></a>
					<a class="link" href="/auth/reg?<?php print $back ?>"><?php print REG ?></a>
				
				<?php if(!$core->user->guest){ ?>
					<a class="link" href="/auth/fast?<?php print $back ?>"><?php print FAST ?></a>
				<?php } ?>
				
			<?php }else{ ?>
				<a class="link" href="/main/links"><?php print LINKS ?></a>
				<?php if(strtolower($core->module) != 'main' OR strtolower($core->action) != 'index'){ ?>
					<a class="link" href="/"><?php print NEW_LINK ?></a>
				<?php } ?>
				<a class="link" href="/auth/logout?<?php print $back ?>"><?php print QUIT ?></a>
			<?php } ?>
		</div>
		
		<h1>ERROR 404: PAGE NOT FOUND!</h1>
		
		<footer>
			<?php if($core->user->admin AND $core->user->logged){ ?>
				<span class="timer">
					<?php print number_format(microtime(true)-TIMER,8); ?>
				</span>
				<hr>
				<span class="inc_files">
					<?php foreach(get_included_files() as $inc){ ?>
						<b><?php print $inc; ?></b><br>
					<?php } ?>
				</span>
				<hr>
				<?php foreach($core->base->query_list as $sql) { ?>
					<div class="query">
						<div class="src"><?php echo $sql['src']; ?></div>
						<?php echo nl2br(htmlspecialchars($sql['sql'])); ?>
						<div class="query_time"><?php echo QUERY_TIME; ?> 
							<span class="<?php echo (($sql['time']>=0.1) ? 'red_query' : 'green_query'); ?>">
							<?php echo number_format($sql['time'], 5); ?></span> <?php echo SECOND ?>
						</div>
					</div>
				<?php } ?>
			<?php } ?>
		</footer>
	</body>
</html>