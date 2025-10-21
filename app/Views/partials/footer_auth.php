<?php $session = session(); ?>
 
    		<!-- Footer -->
		<footer class="footer">
			<div class="footer-bg">
				<img src="assets/img/bg/footer-bg-01.png" class="footer-bg-1" alt="">
				<img src="assets/img/bg/footer-bg-02.png" class="footer-bg-2" alt="">
			</div>

	
			<div class="footer-bottom">
				<div class="container">
					<div class="row row-gap-2">
            <div class="col-md-3">
						</div>
						<div class="col-md-6">
							<div class="text-center text-md-start">
								<p class="text-white">Copyright &copy; <?= date('Y') ?> Edo State University — APER  Signed in as <strong><?= esc($session->get('fullname') ?? $session->get('email')) ?></strong></p>
							</div>
						</div>
						<div class="col-md-3">
						</div>
					</div>
				</div>
			</div>
		</footer>
		<!-- /Footer -->

		</div>
		<!-- /Main Wrapper -->






