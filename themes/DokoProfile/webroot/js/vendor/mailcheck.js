/*
 * Mailcheck https://github.com/mailcheck/mailcheck
 * Author
 * Derrick Ko (@derrickko)
 *
 * Released under the MIT License.
 *
 * v 1.1.0
 */

var Mailcheck = {
  domainThreshold: 4,
  topLevelThreshold: 3,

    defaultDomains: ["yahoo.com", "google.com", "hotmail.com", "gmail.com", "me.com", "aol.com", "mac.com",
        "live.com", "comcast.net", "googlemail.com", "msn.com", "hotmail.co.uk", "yahoo.co.uk",
        "facebook.com", "verizon.net", "sbcglobal.net", "att.net", "gmx.com", "mail.com", "outlook.com", "icloud.com",
        // ITALIAN - by librasoft
        "libero.it", "dominioweb.org", "inwind.it", "telecom.it", "iol.it", "interfree.it", "blu.it",
        "vodafone.it", "kataweb.it", "ciaoweb.it", "email.it", "tele2.it", "supereva.it", "interbusiness.it",
        "yahoo.it", "supereva.it", "poste.it", "jumpy.it", "fastweb.it", "infinito.it", "lycos.it",
        "monrif.net", "galactica.it", "elitel.it", "atlavia.it", "cheapnet.it", "ulisse.it", "191.it",
        "tim.it", "alice.it", "rossoalice.it", "virgilio.it", "everyday.com", "omnitel.it", "vodafone.it",
        "albacom.it", "atlavia.it", "caltanet.it", "cheapnet.it", "elitel.biz", "email.it", "excite.it",
        "hotpop.com", "internetlibero.it", "klik.it", "lillinet.org", "lombardiacom.it", "mclink.it",
        "monrif.net", "neomedia.it", "net4free.it", "ngi.it", "nopay.it", "poste.it", "postino.it",
        "protocol.it", "rimini.com", "sailor.it", "softhome.net", "inet.it", "worldonline.it", "tiscali.it",
        /* Other global domains */
        "email.com", "games.com" /* AOL */, "gmx.net", "hush.com", "hushmail.com", "inbox.com",
        "lavabit.com", "love.com" /* AOL */, "pobox.com", "rocketmail.com" /* Yahoo */,
        "safe-mail.net", "wow.com" /* AOL */, "ygm.com" /* AOL */, "ymail.com" /* Yahoo */, "zoho.com",
        /* United States ISP domains */
        "bellsouth.net", "charter.net", "cox.net", "earthlink.net", "juno.com",
        /* British ISP domains */
        "btinternet.com", "virginmedia.com", "blueyonder.co.uk", "freeserve.co.uk", "live.co.uk",
        "ntlworld.com", "o2.co.uk", "orange.net", "sky.com", "talktalk.co.uk", "tiscali.co.uk",
        "virgin.net", "wanadoo.co.uk", "bt.com",
        /* Chinese ISP domains */
        "sina.com", "qq.com",
        /* French ISP domains */
        "hotmail.fr", "live.fr", "laposte.net", "yahoo.fr", "wanadoo.fr", "orange.fr", "gmx.fr", "sfr.fr", "neuf.fr", "free.fr",
        /* German ISP domains */
        "gmx.de", "hotmail.de", "live.de", "online.de", "t-online.de" /* T-Mobile */, "web.de", "yahoo.de",
        /* Russian ISP domains */
        "mail.ru", "rambler.ru", "yandex.ru",
        /* Belgian ISP domains */
        "hotmail.be", "live.be", "skynet.be", "voo.be", "tvcablenet.be",
        /* Argentinian ISP domains */
        "hotmail.com.ar", "live.com.ar", "yahoo.com.ar", "fibertel.com.ar", "speedy.com.ar", "arnet.com.ar",
        /* Domains used in Mexico */
        "yahoo.com.mx", "live.com.mx", "hotmail.es", "hotmail.com.mx", "prodigy.net.mx"
      ],

    //librasoft added "it" "eu"
    defaultTopLevelDomains: ["it", "eu", "co.jp", "co.uk", "com", "net", "org", "info", "edu", "gov", "mil", "ca"],

  run: function(opts) {
    opts.domains = opts.domains || Mailcheck.defaultDomains;
    opts.topLevelDomains = opts.topLevelDomains || Mailcheck.defaultTopLevelDomains;
    opts.distanceFunction = opts.distanceFunction || Mailcheck.sift3Distance;

    var defaultCallback = function(result){ return result };
    var suggestedCallback = opts.suggested || defaultCallback;
    var emptyCallback = opts.empty || defaultCallback;

    var result = Mailcheck.suggest(Mailcheck.encodeEmail(opts.email), opts.domains, opts.topLevelDomains, opts.distanceFunction);

    return result ? suggestedCallback(result) : emptyCallback()
  },

  suggest: function(email, domains, topLevelDomains, distanceFunction) {
    email = email.toLowerCase();

    var emailParts = this.splitEmail(email);

    var closestDomain = this.findClosestDomain(emailParts.domain, domains, distanceFunction, this.domainThreshold);

    if (closestDomain) {
      if (closestDomain != emailParts.domain) {
        // The email address closely matches one of the supplied domains; return a suggestion
        return { address: emailParts.address, domain: closestDomain, full: emailParts.address + "@" + closestDomain };
      }
    } else {
      // The email address does not closely match one of the supplied domains
      var closestTopLevelDomain = this.findClosestDomain(emailParts.topLevelDomain, topLevelDomains, distanceFunction, this.topLevelThreshold);
      if (emailParts.domain && closestTopLevelDomain && closestTopLevelDomain != emailParts.topLevelDomain) {
        // The email address may have a mispelled top-level domain; return a suggestion
        var domain = emailParts.domain;
        closestDomain = domain.substring(0, domain.lastIndexOf(emailParts.topLevelDomain)) + closestTopLevelDomain;
        return { address: emailParts.address, domain: closestDomain, full: emailParts.address + "@" + closestDomain };
      }
    }
    /* The email address exactly matches one of the supplied domains, does not closely
     * match any domain and does not appear to simply have a mispelled top-level domain,
     * or is an invalid email address; do not return a suggestion.
     */
    return false;
  },

  findClosestDomain: function(domain, domains, distanceFunction, threshold) {
    threshold = threshold || this.topLevelThreshold;
    var dist;
    var minDist = 99;
    var closestDomain = null;

    if (!domain || !domains) {
      return false;
    }
    if(!distanceFunction) {
      distanceFunction = this.sift3Distance;
    }

    for (var i = 0; i < domains.length; i++) {
      if (domain === domains[i]) {
        return domain;
      }
      dist = distanceFunction(domain, domains[i]);
      if (dist < minDist) {
        minDist = dist;
        closestDomain = domains[i];
      }
    }

    if (minDist <= threshold && closestDomain !== null) {
      return closestDomain;
    } else {
      return false;
    }
  },

  sift3Distance: function(s1, s2) {
    // sift3: http://siderite.blogspot.com/2007/04/super-fast-and-accurate-string-distance.html
    if (s1 == null || s1.length === 0) {
      if (s2 == null || s2.length === 0) {
        return 0;
      } else {
        return s2.length;
      }
    }

    if (s2 == null || s2.length === 0) {
      return s1.length;
    }

    var c = 0;
    var offset1 = 0;
    var offset2 = 0;
    var lcs = 0;
    var maxOffset = 5;

    while ((c + offset1 < s1.length) && (c + offset2 < s2.length)) {
      if (s1.charAt(c + offset1) == s2.charAt(c + offset2)) {
        lcs++;
      } else {
        offset1 = 0;
        offset2 = 0;
        for (var i = 0; i < maxOffset; i++) {
          if ((c + i < s1.length) && (s1.charAt(c + i) == s2.charAt(c))) {
            offset1 = i;
            break;
          }
          if ((c + i < s2.length) && (s1.charAt(c) == s2.charAt(c + i))) {
            offset2 = i;
            break;
          }
        }
      }
      c++;
    }
    return (s1.length + s2.length) /2 - lcs;
  },

  splitEmail: function(email) {
    var parts = email.trim().split('@');

    if (parts.length < 2) {
      return false;
    }

    for (var i = 0; i < parts.length; i++) {
      if (parts[i] === '') {
        return false;
      }
    }

    var domain = parts.pop();
    var domainParts = domain.split('.');
    var tld = '';

    if (domainParts.length == 0) {
      // The address does not have a top-level domain
      return false;
    } else if (domainParts.length == 1) {
      // The address has only a top-level domain (valid under RFC)
      tld = domainParts[0];
    } else {
      // The address has a domain and a top-level domain
      for (var i = 1; i < domainParts.length; i++) {
        tld += domainParts[i] + '.';
      }
      if (domainParts.length >= 2) {
        tld = tld.substring(0, tld.length - 1);
      }
    }

    return {
      topLevelDomain: tld,
      domain: domain,
      address: parts.join('@')
    }
  },

  // Encode the email address to prevent XSS but leave in valid
  // characters, following this official spec:
  // http://en.wikipedia.org/wiki/Email_address#Syntax
  encodeEmail: function(email) {
    var result = encodeURI(email);
    result = result.replace('%20', ' ').replace('%25', '%').replace('%5E', '^')
                   .replace('%60', '`').replace('%7B', '{').replace('%7C', '|')
                   .replace('%7D', '}');
    return result;
  }
};

// Export the mailcheck object if we're in a CommonJS env (e.g. Node).
// Modeled off of Underscore.js.
if (typeof module !== 'undefined' && module.exports) {
    module.exports = Mailcheck;
}

// Support AMD style definitions
// Based on jQuery (see http://stackoverflow.com/a/17954882/1322410)
if (typeof define === "function" && define.amd) {
  define("mailcheck", [], function() {
    return Mailcheck;
  });
}

if (typeof window !== 'undefined' && window.jQuery) {
  (function($){
    $.fn.mailcheck = function(opts) {
      var self = this;
      if (opts.suggested) {
        var oldSuggested = opts.suggested;
        opts.suggested = function(result) {
          oldSuggested(self, result);
        };
      }

      if (opts.empty) {
        var oldEmpty = opts.empty;
        opts.empty = function() {
          oldEmpty.call(null, self);
        };
      }

      opts.email = this.val();
      Mailcheck.run(opts);
    }
  })(jQuery);
}
