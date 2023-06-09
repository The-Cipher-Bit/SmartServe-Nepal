import { toast } from "react-toastify";
import { formatDistanceToNow, parseISO } from "date-fns";
//toast
export const handleSuccess = (msg) => {
    // API call is successful
    toast.success(msg, {
        position: toast.POSITION.TOP_RIGHT,
    });
};
export const handleError = (errorData) => {
    // API call resulted in an error
    let errorMessage = "";
     for (const key in errorData) {
         if (errorData.hasOwnProperty(key)) {
             const values = errorData[key].join(", ");
             errorMessage += `${values}\n`;
         }
     }
    toast.error(errorMessage, { position: toast.POSITION.TOP_RIGHT });
};

//get polls with expiry date

const calculateRemainingTime = (endDate) => {
    const formattedDate = endDate.replace(" ", "T").replace(":00", "");
    const end = parseISO(formattedDate);
    const remainingTime = formatDistanceToNow(end);
    return remainingTime;
};

//setAllPolls is a function which sets the state of all polls
export  const updateRemainingTimes = (setAllPolls) => {
        setAllPolls((prev) => {
        const updatedPolls = prev.map((poll) => {
            const timeRemaining = calculateRemainingTime(poll.end_date);
            return { ...poll, remainingTime: timeRemaining };
        });
        // console.log(updatedPolls)
        return updatedPolls;
    });
};
