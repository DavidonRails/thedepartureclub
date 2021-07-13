
<script type="text/ng-template" id="add-alert.html">
	<div class="modal modal-mini" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" aria-label="Close" ng-click="$hide()">
						<span aria-hidden="true"><i class="ti-close"></i>
						</span>
					</button>
					<h3 class="modal-title">Add Alert</h3>
				</div>
				<div class="modal-body" flight-search>
					<div class="alert alert-danger" ng-if="error">
						@{{error}}
					</div>
					<form class="">
						<div class="form-group type">
							<input
								class="form-control"
								ng-model="form.origin_location"
								bs-options="data as data.formatted_address for data in citySearch($viewValue)"
								placeholder="Origin"
								data-animation="am-fade"
								data-min-length="0"
								data-limit="6"
								bs-typeahead
								data-trim-value="false" ng-trim="false"
								type="text">
							<i class="form-control-ico ico-orig"></i>
						</div>
						<div class="form-group type">
							<input
								class="form-control"
								ng-model="form.destination_location"
								bs-options="data as data.formatted_address for data in citySearch($viewValue)"
								placeholder="Destination"
								data-animation="am-fade"
								data-min-length="0"
								data-limit="6"
								bs-typeahead
								data-trim-value="false" ng-trim="false"
								type="text">
							<i class="form-control-ico ico-dest"></i>
						</div>

						<div class="form-group" style="margin-top: 50px;">
							<button class="btn btn-primary btn-block" ng-click="add(form)">
								Add
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</script>
