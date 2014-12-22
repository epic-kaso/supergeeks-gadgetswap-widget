/**
 * Created by kaso on 12/19/2014.
 */

var Gadget = function(props){
  //this.make = props.make || '';
  //this.model = props.model || '';
  //this.color = props.color || '';
  //this.size = props.size || '';
  //this.baseLinePrice = props.baseLinePrice || 0;
  //this.condition = '';
  //this.network = '';
};

Gadget.prototype.getValue = function(){
  var result = this.condition * this.baseLinePrice,
      bonus =  result * (this.getNetworkBonus()/100.0);

    return result + bonus;
};

Gadget.prototype.getNetworkBonus = function(){
    if(this.network == 'airtel')
        return 10;
    return 0;
};

