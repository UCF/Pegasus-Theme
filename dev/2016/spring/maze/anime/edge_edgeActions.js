/***********************
* Adobe Edge Animate Composition Actions
*
* Edit this file with caution, being careful to preserve 
* function signatures and comments starting with 'Edge' to maintain the 
* ability to interact with these actions from within Adobe Edge Animate
*
***********************/
(function($, Edge, compId){
var Composition = Edge.Composition, Symbol = Edge.Symbol; // aliases for commonly used Edge classes

   //Edge symbol: 'stage'
   (function(symbolName) {
      
      
      Symbol.bindElementAction(compId, symbolName, "document", "compositionReady", function(sym, e) {
         // insert code to be run when the composition is fully loaded here

      });
      //Edge binding end

   })("stage");
   //Edge symbol end:'stage'

   //=========================================================
   
   //Edge symbol: 'icon'
   (function(symbolName) {   
   
   })("icon");
   //Edge symbol end:'icon'

   //=========================================================
   
   //Edge symbol: 'PegasusDot'
   (function(symbolName) {   
   
   })("PegasusDot");
   //Edge symbol end:'PegasusDot'

})(window.jQuery || AdobeEdge.$, AdobeEdge, "maze-anime");