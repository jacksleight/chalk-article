<?php if (!$req->isAjax()) { ?>
	<?php $this->outer('/layout/page', [], 'core') ?>
	<?php $this->block('main') ?>
<?php } ?>

<form action="<?= $this->url->route() ?>" method="post" class="flex-col" data-modal-size="800x800">
	<div class="header">
		<ul class="toolbar toolbar-right">
			<li><a href="<?= $this->url([
				'action' => 'index',
				'id'	 => null,
			]) ?>" class="btn btn-out btn-lighter icon-arrow-left">
				Back
			</a></li>
		</ul>
		<h1>
			<?php if (!$category->isNew()) { ?>
				<?= $category->name ?>
			<?php } else { ?>
				New <?= $info->singular ?>
			<?php } ?>
		</h1>
	</div>
	<div class="flex body">
		<fieldset class="form-block">
			<div class="form-legend">
				<h2>General</h2>
			</div>
			<div class="form-items">
				<?= $this->render('/element/form-item', array(
					'entity'	=> $category,
					'name'		=> 'name',
					'label'		=> 'Name',
					'autofocus'	=> true,
				), 'core') ?>
			</div>
		</fieldset>
	</div>
	<fieldset class="footer">
		<ul class="toolbar toolbar-right">
			<li>
				<button class="btn btn-positive icon-ok">
					Save <?= $info->singular ?>
				</button>
			</li>
		</ul>
		<ul class="toolbar">
			<?php if (!$category->isNew()) { ?>
				<li>
					<a href="<?= $this->url([
						'action' => 'delete',
					]) ?>" class="btn btn-negative btn-out confirmable icon-delete">
						Delete <?= $info->singular ?>
					</a>
				</li>
			<?php } ?>
		</ul>
	</fieldset>
</form>