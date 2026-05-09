  </div><!-- /content -->
</main><!-- /main -->

<script>
// Auto-ocultar flash
document.querySelectorAll('.flash').forEach(el=>{
  setTimeout(()=>{el.style.opacity='0';el.style.transform='translateY(-10px)';setTimeout(()=>el.remove(),300)},5000);
});

// Drag & drop zona de archivo
document.querySelectorAll('.file-zone').forEach(zone=>{
  const input = zone.querySelector('input[type=file]');
  const label = zone.querySelector('.file-label');
  if(!input) return;
  zone.addEventListener('click',()=>input.click());
  zone.addEventListener('dragover',e=>{e.preventDefault();zone.style.borderColor='var(--primary)'});
  zone.addEventListener('dragleave',()=>zone.style.borderColor='');
  zone.addEventListener('drop',e=>{
    e.preventDefault();zone.style.borderColor='';
    if(e.dataTransfer.files[0]){
      const dt=new DataTransfer(); dt.items.add(e.dataTransfer.files[0]);
      input.files=dt.files;
      if(label) label.textContent='📎 '+e.dataTransfer.files[0].name;
    }
  });
  input.addEventListener('change',()=>{
    if(input.files[0] && label) label.textContent='📎 '+input.files[0].name;
  });
});
</script>
</body>
</html>
