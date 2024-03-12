export async function getVideos(id = false) {
    try {
        const { data } = await axios.get(`groups/${id ? id :this.id}/videos`, {
            params: !id ? this.pagination : { per_page: 100, page: 1 }
        });
        if (id) {
            const videoIds = []
            data.videos.data.map(d => {
                videoIds.push(d.uri.split('/')[2]);
            });
            return videoIds;
        }
        return data;

    } catch (err) {
        console.log(err)
        return false;
    }
}

export async function getUserVideos(id) {
    try {
        const { data } = await axios.get(`/users/${id}/videos`);
        return data;
    } catch (err) {
        console.log(err)
        return false;
    }
}

export async function removeVideo(group, id) {
    try {
        const { data } = await axios.delete(`/groups/${this.group}/videos/${this.id}`);
        return data;
    } catch (err) {
        console.log(err);
    }
}