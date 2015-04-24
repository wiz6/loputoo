function UserEvent(e) {
    if (!e) {
        return;
    }
    return {
        time: null,
        //location hiljem
        posX: null,
        posY: null,
        eventName: null,
        keyCode: null,
	    sessionID: null,
	    userID: null,
        //user hiljem,
        createEvent: function() {
            this.time = e.timeStamp;
            this.posX = e.clientX;
            this.posY = e.clientY;
            this.eventName = e.type;
            this.keyCode = e.keyCode ? e.keyCode : null;
	        this.sessionID = Tracker.getSessionID();
	        this.userID = Tracker.getUserID();
	        this.location = window.location.pathname;
            return this;
        }
    }
};

Tracker = {
    userEvents: [],
    init: function() {
        if (!window.jQuery) {
            console.log("jQuery is not loaded.");
            return;
        }
        this.startTracking();
        this.beforeUnload();
    },
    startTracking: function() {
        console.log("Tracking started..");
        this.registerEvents();
	    this.registerSessions();
    },
    registerEvents: function() {
        this.registerClickEvent();
        this.registerMouseMoveEvent();
        this.registerKeyUpEvent();
    },
	registerSessions: function() {
		this.removeSessionID();
		this.setUserID();
		this.setSessionID();
	},
    registerClickEvent: function() {
        $(document).bind('click', function(e) {
            console.log("clicked", e);
            var userEvent = new UserEvent(e);
            Tracker.userEvents.push(userEvent.createEvent());
        });
    },
    registerKeyUpEvent: function() {

        $(document).bind('keyup', function(e) {
            var userEvent = new UserEvent(e);
            Tracker.userEvents.push(userEvent.createEvent());
            console.log("keydown",e);
        });
    },
    registerMouseMoveEvent: function() {
        var stop = false;
        $(document).bind('mousemove', function(e) {
            if (stop) {
                return;
            }
            stop = true;
           setTimeout(function() {
                var userEvent = new UserEvent(e);
                Tracker.userEvents.push(userEvent.createEvent());
                console.log("moved");
               stop = false;
           }, 50);
        });
    },
    beforeUnload: function() {
        window.onbeforeunload = function(){
            if (!Tracker.userEvents) {
                return;
            }
            console.log(Tracker.userEvents);

            $.ajax({
                url: '/loputoo/lib/tracker.php?action=saveUserEvents',
                type:'POST',
                data: {
                    userEvents: Tracker.userEvents
                }
            }).done(function(data) {
                console.log(data);
	            Tracker.removeSessionID();
            });
            return 'Are you sure you want to leave?';
        };
    },
	setSessionID: function() {
		if (localStorage.getItem('userEventSessionID')) {
			return;
		}
		localStorage.setItem('userEventSessionID',Math.floor(Math.random() * 1e15) + new Date().getMilliseconds().toString(36).toUpperCase());
	},
	getSessionID: function() {
		return localStorage.getItem('userEventSessionID');
	},
	removeSessionID: function() {
		localStorage.removeItem('userEventSessionID');
	},
	setUserID: function() {
		if (localStorage.getItem('userEventUserID')) {
			return;
		}
		localStorage.setItem('userEventUserID',Math.floor(Math.random() * 1e6) + new Date().getMilliseconds().toString(36).toUpperCase());
	},
	getUserID: function() {
		return localStorage.getItem('userEventUserID');
	}
};

//run the tracker
Tracker.init();

