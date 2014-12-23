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
    if(this.network)
        return this.network.description;
    return 'none';
};

Gadget.prototype.getReward = function(){
    if(this.size && this.baseLinePrice && this.condition_value)
        return parseInt(parseInt(this.baseLinePrice) * (this.condition_value/ 100.0));
    return 'Sorry, You\'ll have to come to our Swap Location.';
};

