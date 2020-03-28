<header id="single-header">
<div class="row">
	<div class="col-md-12 text-center">
		<h1>
			<?= $locales->RAIDS_TITLE; ?>
		</h1>
	</div>
</div>
</header>

<div class="row text-center subnav">
	<div class="btn-group" role="group">
		<a class="btn btn-default raidLevel active" data-level="0"><?= $locales->RAIDS_ALL_LEVELS; ?></a>
		<a class="btn btn-default raidLevel" data-level="1"><?= $locales->RAIDS_TABLE_LEVEL; ?> 1</a>
		<a class="btn btn-default raidLevel" data-level="2"><?= $locales->RAIDS_TABLE_LEVEL; ?> 2</a>
		<a class="btn btn-default raidLevel" data-level="3"><?= $locales->RAIDS_TABLE_LEVEL; ?> 3</a>
		<a class="btn btn-default raidLevel" data-level="4"><?= $locales->RAIDS_TABLE_LEVEL; ?> 4</a>
		<a class="btn btn-default raidLevel" data-level="5"><?= $locales->RAIDS_TABLE_LEVEL; ?> 5</a>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="table-responsive">
			<table class="table" id="raidsTable">
				<thead>
				<tr>
					<th><?= $locales->RAIDS_TABLE_LEVEL; ?></th>
					<th style="min-width:100px""><?= $locales->RAIDS_TABLE_TIME; ?></th>
					<th><?= $locales->RAIDS_TABLE_REMAINING; ?></th>
					<th><?= $locales->RAIDS_TABLE_GYM; ?></th>
					<th colspan="2"><?= $locales->RAIDS_TABLE_BOSS; ?></th>
				</tr>
				</thead>
				<tbody id="raidsContainer">

				</tbody>
				<tfoot>
					<tr class="loadMore text-center">
						<td colspan="6"><button id="loadMoreButton" class="btn btn-default hidden"><?= $locales->RAIDS_LOAD_MORE; ?></button></td>
					</tr>
					<tr class="raidsLoader">
						<td colspan="6"><div class="loader"></div></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>