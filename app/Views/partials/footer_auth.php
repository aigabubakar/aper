<?php $session = session(); ?>
 <!-- footer start-->
        <footer class="footer">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-12 footer-copyright text-center">
                 <div>&copy; <?= date('Y') ?> Edo State University — APER  Signed in as <strong><?= esc($session->get('fullname') ?? $session->get('email')) ?></strong></div>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>
 