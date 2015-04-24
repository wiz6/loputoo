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
        //user hiljem,
        createEvent: function() {
            this.time = e.timeStamp;
            this.posX = e.clientX;
            this.posY = e.clientY;
            this.eventName = e.type;
            this.keyCode = e.keyCode ? e.keyCode : null;

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
    },
    registerEvents: function() {
        this.registerClickEvent();
        this.registerMouseMoveEvent();
        this.registerKeyUpEvent();
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
                url: '/loputoo/lib/tracker.php?action=sendUserEvents',
                type:'POST',
                data: {
                    userEvents: Tracker.userEvents
                }
            }).done(function(data) {
                console.log(data);
            });
            return 'Are you sure you want to leave?';
        };
    }
};

//run the tracker
Tracker.init();

