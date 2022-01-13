<div class="contacts-container">
  <div style="text-align:center">
    <h2>Contact Us</h2>
    <p>Any feedback is always much appreciated! Leave us a message:</p>
  </div>
  <div class="contacts-row">
    <div class="contacts-column">
      <img src="{{url('storage/images/feup_location.png')}}" style="width:100%">
    </div>
    <div class="contacts-column">
      <form action="{{ route('contacts') }}">
        <label for="fname">First Name</label>
        <input class = "contacts-input-text" type="text" id="fname" name="firstname" placeholder="Your name..">
        <label for="lname">Last Name</label>
        <input class = "contacts-input-text" type="text" id="lname" name="lastname" placeholder="Your last name..">
        <label for="country">Country</label>
        <select class = "contacts-select" id="country" name="country">
          <option value="portugal">Portugal</option>
          <option value="spain">Spain</option>
          <option value="usa">USA</option>
        </select>
        <label for="subject">Subject</label>
        <textarea class = "contacts-textarea" id="subject" name="subject" placeholder="Write something.." style="height:170px"></textarea>
        <input class="contacts-input-submit" type="submit" value="Submit">
      </form>
    </div>
  </div>
</div>