FROM debian
LABEL maintainer="Lerte Smith <lerte@qq.com>"

RUN DEBIAN_FRONTEND=noninteractive apt-get update && \
    apt-get install -y git \
                       curl \
                       wget \
                       libssl-dev \
                       gnupg \
                       gosu \
                       procps \
                       build-essential \
                       apt-transport-https \
                       python 

# Install nvm
RUN git clone https://github.com/creationix/nvm.git $NVM_DIR && \
    cd $NVM_DIR && \
    git checkout `git describe --abbrev=0 --tags --match "v[0-9]*" origin`

# Install latest version of Node.js
RUN . $NVM_DIR/nvm.sh && \
    nvm install node

# Add nvm.sh to .bashrc for startup...
RUN echo "source ${NVM_DIR}/nvm.sh" > $HOME/.bashrc && \
    . $HOME/.bashrc

# Install yarn
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - && \
    echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list && \
    apt-get update && apt-get install -y yarn

RUN mkdir -p /root/workspace
ENV WORKSPACE /root/workspace
VOLUME $WORKSPACE
WORKDIR $WORKSPACE