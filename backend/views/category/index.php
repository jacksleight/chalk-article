<?php if (!$req->isAjax()) { ?>
	<?php $this->outer('/layout/page', [], 'core') ?>
	<?php $this->block('main') ?>
	<?php } ?>
<?php
$index = $this->em->wrap(new \Chalk\Core\Model\Index())
	->graphFromArray($req->queryParams());
$tags = $this->em($info)
	->all($index->toArray(), [], Chalk\Repository::FETCH_ALL_PAGED);
?>

<div class="flex-col" data-modal-size="800x800">
	<div class="header">
		<ul class="toolbar toolbar-right">
			<li>
				<a href="<?= $this->url([
					'action' => 'edit',
				]) ?>" class="btn btn-focus icon-add">
					Add <?= $info->singular ?>
				</a>
			</li>
		</ul>
		<h1><?= $info->plural ?></h1>
	</div>
	<div class="flex body">
		<div class="hanging">
			<form action="<?= $this->url->route() ?>" class="submitable">
				<ul class="toolbar">
					<li class="flex">
						<?= $this->render('/element/form-input', array(
							'type'			=> 'input_search',
							'entity'		=> $index,
							'name'			=> 'search',
							'placeholder'	=> 'Search…',
						), 'core') ?>
					</li>
				</ul>
			</form>
		</div>
		<table>
			<colgroup>
				<col class="">
			</colgroup>
			<thead>
				<tr>
					<th scope="col" class="">Name</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($tags as $tag) { ?>
					<tr class="clickable">
						<th class="" scope="row">
							<a href="<?= $this->url([
								'action'	=> 'edit',
								'id'		=> $tag->id,
							]) ?>">
								<?= $tag->name ?>
							</a>
						</th>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<div class="footer">
		<ul class="toolbar toolbar-right">
			<li>
				<button class="btn btn-positive icon-ok modal-close">
					Done
				</button>
			</li>
		</ul>
		<?= $this->render('/element/form-input', [
		    'type'      => 'paginator',
		    'entity'    => $index,
		    'name'      => 'page',
		    'limit'     => $index->limit,
		    'count'     => $tags->count(),
		], 'core') ?>		
	</div>
</div>