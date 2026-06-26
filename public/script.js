document.addEventListener('DOMContentLoaded', async function () {
  const playlistURL = 'api.php';
  let playlistItems = [];
  let favorites = JSON.parse(localStorage.getItem('favoriteStreams')) || [];
  let favoritesOnly = false;

  const playlistElement = document.getElementById('playlist-sidebar');
  const searchInput = document.getElementById('search-input');
  const favoritesToggle = document.getElementById('favorites-toggle');

  // Clappr Player
  const player = new Clappr.Player({
    parentId: "#player",
    width:'100%',
    height:'100%',
    autoPlay:true,
    playback:{hlsjsConfig:{debug:true}}
  });

  function playStream(url){
    let sourceConfig = { source: url };
    if(url.includes('.m3u8')) sourceConfig.mimeType='application/x-mpegURL';
    else if(url.includes('.ts')) sourceConfig.mimeType='video/MP2T';
    player.load(sourceConfig);
  }

  // Load playlist
  try {
    const res = await fetch(playlistURL);
    const channels = await res.json();
    playlistItems = channels.map(ch => ({ title: ch.name, url: ch.url, logo: ch.logo || '' }));
    renderPlaylist();
    if(playlistItems.length>0) playStream(playlistItems[0].url);
  } catch(e){ console.error('Erreur playlist:', e); }

  function renderPlaylist(){
    playlistElement.innerHTML = '';
    playlistItems.filter(item=>{
      if(favoritesOnly && !favorites.includes(item.url)) return false;
      if(searchInput.value && !item.title.toLowerCase().includes(searchInput.value.toLowerCase())) return false;
      return true;
    }).forEach(item=>{
      const li = document.createElement('li');
      li.className='list-group-item';
      li.innerHTML = item.logo ? `<img src="${item.logo}" width="24"> ${item.title}` : item.title;
      li.addEventListener('click', ()=>playStream(item.url));
      playlistElement.appendChild(li);
    });
  }

  // Toggle favorites
  favoritesToggle.addEventListener('click', ()=>{
    favoritesOnly = !favoritesOnly;
    favoritesToggle.textContent = favoritesOnly?'Afficher tous':'Afficher les Favoris';
    renderPlaylist();
  });

  searchInput.addEventListener('input', renderPlaylist);

  // Toggle sidebars
  const sidebarLeft = document.getElementById('sidebar-left');
  const sidebarRight = document.getElementById('sidebar-right');
  const overlay = document.getElementById('overlay');

  function slideSidebar(sidebar, show=true){
    if(show){
      sidebar.classList.add('active');
      overlay.classList.add('show');
    } else {
      sidebar.classList.remove('active');
      overlay.classList.remove('show');
    }
  }

  document.getElementById('toggle-left').addEventListener('click', ()=>{
    const show = !sidebarLeft.classList.contains('active');
    slideSidebar(sidebarLeft, show);
    slideSidebar(sidebarRight, false);
  });

  document.getElementById('toggle-right').addEventListener('click', ()=>{
    const show = !sidebarRight.classList.contains('active');
    slideSidebar(sidebarRight, show);
    slideSidebar(sidebarLeft, false);
  });

  overlay.addEventListener('click', ()=>{
    slideSidebar(sidebarLeft,false);
    slideSidebar(sidebarRight,false);
  });

 const dateElement = document.getElementById("current-date");
  
  function updateDate() {
    const now = new Date();
    const options = { 
      weekday: "short", 
      year: "numeric", 
      month: "short", 
      day: "numeric", 
      hour: "2-digit", 
      minute: "2-digit" 
    };
    dateElement.textContent = now.toLocaleDateString("fr-FR", options);
  }
  
  updateDate();
  setInterval(updateDate, 60000); // mise à jour chaque minute


  // Hide loading screen
  setTimeout(()=>{ document.getElementById('loading-screen').style.display='none'; },1500);
});
