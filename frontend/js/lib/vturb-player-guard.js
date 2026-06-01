/**
 * VTurb / ConverteAI guard: no autoplay, muted by default, play only after
 * interaction inside the smartplayer host. Dashboard config can override embed
 * attributes; this enforces behavior on injected <video> elements.
 */
(function () {
  var SMR = (window.SMR = window.SMR || {});

  function getGuardedHosts() {
    return document.querySelectorAll('vturb-smartplayer[data-smr-guard="1"]');
  }

  function ensureHostListeners(host) {
    if (host.__smrVturbHostGuarded) {
      return;
    }
    host.__smrVturbHostGuarded = true;
    host.__smrVturbAllowPlay = false;

    var allowPlayFromHost = function () {
      host.__smrVturbAllowPlay = true;
    };

    host.addEventListener('pointerdown', allowPlayFromHost, true);
    host.addEventListener('click', allowPlayFromHost, true);
    host.addEventListener('touchstart', allowPlayFromHost, true);
  }

  function guardVideo(host, video) {
    if (!video || video.__smrVturbVideoGuarded) {
      return;
    }
    video.__smrVturbVideoGuarded = true;
    video.autoplay = false;
    video.removeAttribute('autoplay');
    video.muted = true;
    video.defaultMuted = true;
    video.setAttribute('muted', '');

    video.addEventListener(
      'play',
      function () {
        if (!host.__smrVturbAllowPlay) {
          try {
            video.pause();
          } catch (e) {
            /* ignore */
          }
        }
      },
      true
    );
  }

  function scanHost(host) {
    ensureHostListeners(host);
    var videos = host.querySelectorAll('video');
    for (var i = 0; i < videos.length; i++) {
      guardVideo(host, videos[i]);
    }
  }

  function guardVturbPlayers() {
    var hosts = getGuardedHosts();
    for (var i = 0; i < hosts.length; i++) {
      scanHost(hosts[i]);
    }
  }

  function install() {
    guardVturbPlayers();
    var observer = new MutationObserver(function () {
      guardVturbPlayers();
    });
    observer.observe(document.documentElement, { childList: true, subtree: true });
  }

  SMR.guardVturbPlayers = guardVturbPlayers;

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', install);
  } else {
    install();
  }
})();
