<div class="col-md-8"> 
        <?php if( $success ): ?>
          <div class="alert alert-success "><?php echo $successText; ?></div>
        <?php endif; ?>
           <?php if( $error ): ?>
          <div class="alert alert-danger "><?php echo $errorText; ?></div>
        <?php endif; ?>
        
        <div class="panel panel-default">
    <div class="panel-body">
      <form action="" method="post" enctype="multipart/form-data">
          <div id="pop_noti" data-toggle="buttons" class="btn-group btn-group-sm btn-group-justified">
														
														
													</div>
          <div class="form-group">
          <label class="control-label">BROADCAST        WORK IN PROGRESS</label>
          <select class="form-control" name="pop_noti"> 
            <option value="1" <?= $decoration["pop_noti"] == 1 ? "selected" : null; ?>>Active</option>
            <option value="0" <?= $decoration["pop_noti"] == 0 ? "selected" : null; ?>>Passive</option>
          </select></div>
          
             <div class="form-group">
          <label class="control-label">Title</label>
          <input type="text" class="form-control" name="pop_title" value="<?=$decoration["pop_title"]?>">
        </div>
        
        <div class="form-group">
          <label class="control-label">Description</label>
          <textarea class="form-control" rows="7" name="pop_desc" placeholder='New Feature By Psw'><?=$decoration["pop_desc"]?></textarea>
        </div>
        
        
        <div class="form-group">
          <label class="control-label">Action Button</label>
          <select class="form-control" name="action_button"> 
            <option value="1" <?= $decoration["action_button"] == 1 ? "selected" : null; ?>>Active</option>
            <option value="0" <?= $decoration["action_button"] == 0 ? "selected" : null; ?> >Passive</option>
          </select></div>
          
          <div class="form-group">
          <label class="control-label">Button Link</label>
          <input type="text" class="form-control" name="action_link" value="<?=$decoration["action_link"]?>">
        </div>
        <div class="form-group">
          <label class="control-label">Button Text</label>
          <input type="text" class="form-control" name="action_text" value="<?=$decoration["action_text"]?>">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        
      </form>
    


</div></div>
  </div>
  
  
