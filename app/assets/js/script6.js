const notyf = new Notyf({
  duration: 2000,
  position: {
    x: 'right',
    y: 'top',
  }
});

htmx.on("showMessage", (e) => {
  if(JSON.parse(e.detail.value).type == "success"){
    document.getElementById('close').click()
  };
  notyf.success(JSON.parse(e.detail.value))
})