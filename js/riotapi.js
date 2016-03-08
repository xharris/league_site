var riotAPI = {
    key : API_KEY,

    // callback(username, level)
    getUserLevel : function(user, callback) {
        call = 'https://na.api.pvp.net/api/lol/na/v1.4/summoner/by-name/' + user.summoner_name + '/?api_key='+this.key;


        $.ajax({
            url: call,
            type: 'GET',
            dataType: 'json',
            data: {

            },
            success: function (json) {
                var SUMMONER_NAME_NOSPACES = user.summoner_name.replace(" ","");
                SUMMONER_NAME_NOSPACES = SUMMONER_NAME_NOSPACES.toLowerCase().trim();

                summoner_level = json[SUMMONER_NAME_NOSPACES].summonerLevel;

                callback(user, summoner_level);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                
            }
        });
    }
}
