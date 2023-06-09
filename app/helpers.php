
<?php
use App\Models\Poll;    
use Illuminate\Support\Facades\Auth;
use App\Models\User;

if(!function_exists('getUserAssociatedActivePolls')){
    
    function getUserAssociatedActivePolls(){
    
        $userId=Auth::user()->id;
        //getting all the active polls voted and not voted by the user
         $user_with_votes = User::with('pollsVoted')->find($userId);
         $votedIds = [];
        foreach ($user_with_votes->pollsVoted as $poll) {
            $votedIds[] = $poll->poll_id;
        }
        $user_voted_active_polls = Poll::where('status', 'active')->whereIn('id', $votedIds)->with('pollOptions')->get();
        $user_not_voted_active_polls = Poll::where('status', 'active')->whereNotIn('id', $votedIds)->with('pollOptions')->get();
        $data = ['user_voted'=>$user_voted_active_polls,'user_not_voted'=>$user_not_voted_active_polls];
        return $data;
    }
}

if(!function_exists('getPollDataWithTotalVotes')){
        
        function getPollDataWithTotalVotes($polls){
       $finalData =  $polls->map(function ($poll) {
            $totalVotes = $poll->pollOptions->sum('votes_count');
            $pollOptionsWithPercentage = $poll->pollOptions->map(function ($option) use ($totalVotes) {
                $option->percentage = ($totalVotes > 0) ? round(($option->votes_count / $totalVotes) * 100, 2) : 0;
                return $option;
            });
            $poll->poll_options = $pollOptionsWithPercentage;
            $poll->totalVotes = $totalVotes;
            return $poll;
        });
        return $finalData;
        }
}

?>