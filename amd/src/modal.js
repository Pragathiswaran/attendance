import Modal from 'core/modal';

export const init = async () => {
    const modal = await Modal.create({
        title: 'Test title',
        body: '<p>Example body content</p>',
        footer: 'An example footer content',
        show: true,
        removeOnClose: true,
    });
}