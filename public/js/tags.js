$(document).ready(function() {
    $('#myForm').on('keydown', function(event) {
    if (event.key === "Enter") {
      event.preventDefault();
    }
  });

 
});

const input = document.getElementById('tagInput');
const errorSpan = document.getElementById('tagError');
new Tagify(input, {
mode: 'text',
  delimiters: ',',
  maxTags: 3,
  placeholder: 'Enter tags',
  addTagOnBlur: true,
  duplicates: false,
});

