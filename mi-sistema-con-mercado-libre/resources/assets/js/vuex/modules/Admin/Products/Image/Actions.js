export const image_remove = async (_, img) => {

    try {
        const image = await axios.post('/api/products/image_remove', {img})

        return image;

    } catch (e) {
        throw e;
    }
}