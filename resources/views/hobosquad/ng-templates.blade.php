
<script type="text/ng-template" id="invite-friends.html">
	<div class="modal modal-mini" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" aria-label="Close" ng-click="$hide()">
						<span aria-hidden="true"><i class="ti-close"></i>
						</span>
					</button>
					<h3 class="modal-title">Invite Friends</h3>
				</div>
				<div class="modal-body">
					<p>Invite Friends to join your Hobo Squad and become eligible for awesome rewards.</p>
					<div class="list-group invite-group">
						<a href="javascript:void(0)" class="list-group-item" ng-click="fb()">
							Invite via Facebook
							<span class="list-ico">
								<i class="ti-facebook"></i>
							</span>
						</a>
						<a href="@{{branch_invite}}" target="_blank" class="list-group-item">
							Invite via SMS
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>
