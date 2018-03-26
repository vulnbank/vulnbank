<?php
  echo('<div id="transactions-modal" class="modal fade" role="dialog">');
  echo('<div class="modal-dialog">');
  echo('<div class="modal-content">');
  echo('<div class="modal-header">');
  echo('<button type="button" class="close" data-dismiss="modal">&times;</button>');
  echo('<h4 class="modal-title"><center>OTP Verification</center></h4>');
  echo('</div>');
  echo('<div class="modal-body">');
  echo('<div class="form-group">');
  echo('<label>Insert OTP code here</label>');
  echo('<input id="transactions-code" type="text" class="form-control" placeholder="CODE">');
  echo('<input id="transactions-id" type="hidden" class="form-control" value="NONE">');
  echo('</div>');
  echo('</div>');
  echo('<div class="modal-footer">');
  echo('<button type="button" style="float:left;width:150px;" class="btn btn-danger" data-dismiss="modal">Cancel</button>');
  echo('<button id="transactions-code-send" type="button" style="float:right;width:150px;" class="btn btn-info">Confirm</button>');
  echo('</div>');
  echo('</div>');
  echo('</div>');
  echo('</div>');
?>
