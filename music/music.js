/**
 * cloutsy player
 * version: 0.2.1
 */

const playerMusics = [
    {
        name: 'Cant Take My Eyes Off You',
        src: 'https://rr1---sn-cgpn5oxu-wqvd.googlevideo.com/videoplayback?expire=1655345892&ei=hD6qYvPsF6aG_9EP7JqT4A4&ip=3.236.117.68&id=o-AIsGSRrFyZNOsYteProlN8rWA7xsrG10cWnpCEYb1wD8&itag=140&source=youtube&requiressl=yes&vprv=1&mime=audio%2Fmp4&gir=yes&clen=3904082&dur=241.185&lmt=1575118352048695&keepalive=yes&fexp=24001373,24007246,24208265&c=ANDROID&txp=5531432&sparams=expire%2Cei%2Cip%2Cid%2Citag%2Csource%2Crequiressl%2Cvprv%2Cmime%2Cgir%2Cclen%2Cdur%2Clmt&sig=AOq0QJ8wRAIgBPLImL04mklNb_lXX4ZFJR9dyxHh_gEFfFWXTs99QqYCIEBBg0b0IAuCjkrigztaG7rfbzGJjjDA1Dhh4p1wfQ4F&title=Can%27t+Take+My+Eyes+Off+You&redirect_counter=1&rm=sn-p5qyy7s&req_id=8666e4c33892a3ee&cms_redirect=yes&ipbypass=yes&mh=kl&mip=187.19.171.68&mm=31&mn=sn-cgpn5oxu-wqvd&ms=au&mt=1655323996&mv=m&mvi=1&pl=23&lsparams=ipbypass,mh,mip,mm,mn,ms,mv,mvi,pl&lsig=AG3C_xAwRQIgKfHDhr1LfBP1BAGNta7fb1og315HUUgzkLuir1g1r-MCIQCHNjxfMmBGOeiSDk1qBH4wES84od5KMN6XokT-gJzcKA%3D%3D',
        cover: 'https://cdn.discordapp.com/attachments/795128161490698284/986728065877049394/01248ee8-ca23-4fe6-b32e-6ac5fc9cb9c5_mq.jpg',
    },
    {
        name: 'Louis The Child - Big Time',
        src: 'https://cdn.smmspot.net/cloutsy/assets/music/song2.mp3',
        cover: 'https://cdn.smmspot.net/cloutsy/assets/music/song2.jpg',
    },
    {
        name: 'Sea Song - Fakear',
        src: 'https://cdn.smmspot.net/cloutsy/assets/music/song3.mp3',
        cover: 'https://cdn.smmspot.net/cloutsy/assets/music/song3.jpeg',
    },
	{
        name: 'A$AP Rocky - Praise The Lord (Da Shine)',
        src: 'https://cdn.smmspot.net/cloutsy/assets/music/song4.mp3',
        cover: 'https://cdn.smmspot.net/cloutsy/assets/music/song4.jpeg',
    },
	{
        name: 'JVKE - this is what falling in love feels like',
        src: 'https://cdn.smmspot.net/cloutsy/assets/music/song5.mp3',
        cover: 'https://cdn.smmspot.net/cloutsy/assets/music/song5.jpeg',
    },
	{
        name: 'J.Cole - She Knows',
        src: 'https://cdn.smmspot.net/cloutsy/assets/music/song6.mp3',
        cover: 'https://cdn.smmspot.net/cloutsy/assets/music/song6.jpeg',
    },
	{
        name: 'Jnr Choi - TO THE MOON',
        src: 'https://cdn.smmspot.net/cloutsy/assets/music/song7.mp3',
        cover: 'https://cdn.smmspot.net/cloutsy/assets/music/song7.jpeg',
    },
	{
        name: 'WILLOW, Tyler Cole - Meet Me At Our Spot',
        src: 'https://cdn.smmspot.net/cloutsy/assets/music/song8.mp3',
        cover: 'https://cdn.smmspot.net/cloutsy/assets/music/song8.jpeg',
    },
	{
        name: 'NEIKED, Mae Muller, J Balvin - Better Days ft. Polo G',
        src: 'https://cdn.smmspot.net/cloutsy/assets/music/song9.mp3',
        cover: 'https://cdn.smmspot.net/cloutsy/assets/music/song9.jpeg',
    },
	{
        name: 'Quinn XCII - Straightjacket',
        src: 'https://cdn.smmspot.net/cloutsy/assets/music/song10.mp3',
        cover: 'https://cdn.smmspot.net/cloutsy/assets/music/song10.jpeg',
    },
	{
        name: 'Tai Verdes - LAst dAy oN EaRTh',
        src: 'https://cdn.smmspot.net/cloutsy/assets/music/song11.mp3',
        cover: 'https://cdn.smmspot.net/cloutsy/assets/music/song11.jpeg',
    },
	{
        name: 'Charlie Puth - Light Switch',
        src: 'https://cdn.smmspot.net/cloutsy/assets/music/song12.mp3',
        cover: 'https://cdn.smmspot.net/cloutsy/assets/music/song12.jpeg',
    },
	{
        name: 'AURORA - Runaway',
        src: 'https://cdn.smmspot.net/cloutsy/assets/music/song13.mp3',
        cover: 'https://cdn.smmspot.net/cloutsy/assets/music/song13.jpeg',
    },
	{
        name: 'Ed Sheeran - Shape of You',
        src: 'https://cdn.smmspot.net/cloutsy/assets/music/song14.mp3',
        cover: 'https://cdn.smmspot.net/cloutsy/assets/music/song14.jpeg',
    },
	{
        name: 'G-Eazy - I Mean It Ft. Remo',
        src: 'https://cdn.smmspot.net/cloutsy/assets/music/song15.mp3',
        cover: 'https://cdn.smmspot.net/cloutsy/assets/music/song15.jpeg',
    },
	{
        name: 'The Beat Drops - Carlton E Bynum II',
        src: 'https://cdn.smmspot.net/cloutsy/assets/music/song16.mp3',
        cover: 'https://cdn.smmspot.net/cloutsy/assets/music/song16.jpeg',
    },
	{
        name: 'GAYLE - â€‹ABCDEFU',
        src: 'https://cdn.smmspot.net/cloutsy/assets/music/song17.mp3',
        cover: 'https://cdn.smmspot.net/cloutsy/assets/music/song17.jpeg',
    },
	{
        name: 'Doja Cat, The Weeknd - You Right',
        src: 'https://cdn.smmspot.net/cloutsy/assets/music/song18.mp3',
        cover: 'https://cdn.smmspot.net/cloutsy/assets/music/song18.jpeg',
    },
	{
        name: 'Phantogram - Fall In Love',
        src: 'https://cdn.smmspot.net/cloutsy/assets/music/song19.mp3',
        cover: 'https://cdn.smmspot.net/cloutsy/assets/music/song19.jpeg',
    },
];

const sidebarPlayer = document.querySelector('.sidebar-player');
const [activeSong, setActiveSong] = useState(0);
const [autoPlay, setAutoPlay] = useState('disabled');
const [firstPlay, setFirstPlay] = useState('true');

if (sidebarPlayer) {
    const albumCover = sidebarPlayer.querySelector('.album-cover > img');
    const songName = sidebarPlayer.querySelector('.song-name');
    const autoPlayBtn = sidebarPlayer.querySelector('#sound-autoplay');
    const totalSongDom = sidebarPlayer.querySelector('#total-song');
    const currentSongDom = sidebarPlayer.querySelector('#current-song-order');

    totalSongDom.innerHTML = playerMusics.length;

    if (localStorage.getItem('activeSong')) {
        setActiveSong(parseInt(localStorage.getItem('activeSong')));
    }

    if (localStorage.getItem('autoPlay')) {
        setAutoPlay(localStorage.getItem('autoPlay'));
    } else {
        localStorage.setItem('autoPlay', 'enabled');
    }

    const songPosition = (localStorage.getItem('songPosition')) ? parseInt(localStorage.getItem('songPosition')) : 0;

    const setActive = () => {
        albumCover.src = `${playerMusics[activeSong()].cover}`;
        songName.innerHTML = playerMusics[activeSong()].name;
        currentSongDom.innerHTML = activeSong() + 1;
    }

    // prepare the player
    let pi = 0;
    var sound = [];
    playerMusics.forEach(music => {
        const soundItem = new Howl({
            src: [playerMusics[pi].src],
            html5: true,
            preload: false,
            onplay: function () {
                sidebarPlayer.classList.add('playing');
                if (firstPlay() == 'true') {
                    soundItem.seek(songPosition);
                    setFirstPlay('false');
                }
                // watch position
                var positionFunc = () => {
                    var duration = soundItem.duration();
                    var position = soundItem.seek();
                    localStorage.setItem('songPosition', position);
                    this.savePosition = setTimeout(() => positionFunc(), 1000);
                }
                positionFunc();
            },
            onstop: function () {
                clearTimeout(this.savePosition);
            },
            onpause: function () {
                sidebarPlayer.classList.remove('playing');
            },
            onend: function () {
                nextSong();
            }
        });
        sound.push(soundItem);
        pi++;
    })

    if (autoPlay() === 'enabled') {
        autoPlayBtn.classList.add('enabled');
        sound[activeSong()].play();
        setActive();
    } else {
        setActive();
    }

    // next song
    const nextSong = () => {
        sound[activeSong()].stop();
        if (activeSong() < playerMusics.length - 1) {
            setActiveSong(parseInt(activeSong()) + 1);
        } else {
            setActiveSong(0);
        }
        sound[activeSong()].play();
        localStorage.setItem('activeSong', activeSong());

        setActive();
    }

    // previous song
    const prevSong = () => {
        sound[activeSong()].stop();
        if (activeSong() > 0) {
            setActiveSong(parseInt(activeSong()) - 1);
        } else {
            setActiveSong(playerMusics.length - 1);
        }
        sound[activeSong()].play();
        localStorage.setItem('activeSong', activeSong());
        setActive();
    }

    sidebarPlayer.querySelector('#song-next').addEventListener('click', nextSong);
    sidebarPlayer.querySelector('#song-prev').addEventListener('click', prevSong);

    // play and pause
    sidebarPlayer.querySelector('#play-pause').addEventListener('click', () => {
        if (sound[activeSong()].playing()) {
            sound[activeSong()].pause();
            sidebarPlayer.classList.remove('playing');
        } else {
            sound[activeSong()].play();
            sidebarPlayer.classList.add('playing');
        }
    });

    // auto play button
    autoPlayBtn.addEventListener('click', () => {
        if (autoPlay() === 'enabled') {
            autoPlayBtn.classList.remove('enabled');
            localStorage.setItem('autoPlay', 'disabled');
            setAutoPlay('disabled');
        } else {
            autoPlayBtn.classList.add('enabled');
            localStorage.setItem('autoPlay', 'enabled');
            setAutoPlay('enabled');
        }
    });
}