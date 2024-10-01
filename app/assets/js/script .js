const notyf = new Notyf({
  duration: 2000,
  position: {
    x: 'right',
    y: 'top',
  }
});

htmx.on("showMessage", (e) => {
  if(JSON.parse(e.detail.value).close != ""){
    let trigger = JSON.parse(e.detail.value).close;
    document.getElementById(trigger).click();
  };
  notyf.success(JSON.parse(e.detail.value));
});

htmx.on('listChanged', function(event) {
  table.ajax.reload(null, false);
});

