 <div class="row">
        <section class="col-lg-12">
        <form id="form_check" action="<?php echo site_url('Manageuser/acc'); ?>" method="POST">
          <!-- Data list table --> 
            <table class="table table-striped table-bordered" id="userData" width="100%">
                <thead class="thead-dark">
                    <tr>
                        <th></th>
                        <th>Email</th>
                        <th>Level</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
          
          <input type="submit" class="btn btn-success" id="submit" value="Submit">
          <p><b>Selected rows data:</b></p>
          <pre id="example-console-rows"></pre>

          <p><b>Form data as submitted to the server:</b></p>
          <pre id="example-console-form"></pre>

        </form>
        </section>
      </div>