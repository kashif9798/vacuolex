<div
  class="modal fade text-left"
  id="deleteMicrobe{{ $microbe->id }}"
  role="dialog"
  aria-hidden="true"
>
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Delete Microbe</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete the microbe <strong>{{ $microbe->title }}</strong>?
      </div>
      <div class="modal-footer">
        <form method="POST" action="{{ route("microbes.destroy", $microbe) }}">
          @csrf
          @method("DELETE")
          <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>